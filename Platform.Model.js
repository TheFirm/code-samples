'use strict';

app.factories.PlatformModel = function (WebAPI, $q) {
    var Platform = function (data) {
        parseData(this, data);

        this.selectedClient = null;
    };

    function parseData(context, data) {
        angular.extend(context, {
            agency_id: data.agency_id,
            created_at: data.created_at,
            has_dev_token: data.has_dev_token,
            link: data.link,
            status: data.status,
            updated_at: data.updated_at
        });
    }

    Platform.prototype.isConnected = function () {
        return this.hasLogin;
    };

    Platform.prototype.disconnect = function (loginId) {
        var deferred = $q.defer(),
            _this = this;

        WebAPI.getPlatformToDisconnect({
            params: {"id": loginId}
        }).then(function (resp) {
            parseData(_this, resp.data);
            deferred.resolve(_this);
        }, deferred.reject);
        return deferred.promise;
    };

    Platform.prototype.synchronize = function () {
        var deferred = $q.defer(),
            _this = this;
        WebAPI.getPlatformToSync({"id": this.id}).then(function (resp) {
            deferred.resolve(_this);
        }, deferred.reject);
        return deferred.promise;
    };

    Platform.prototype.synchronizeCampainsData = function (clientId) {
        return WebAPI.platformSyncData(clientId);
    };

    Platform.prototype.login = function (login, password) {
        if (this.isNonOauthLogin()) {
            return WebAPI.platformAdFormLogin({"login": login, "password": password});
        } else {
            return $q.reject(false);
        }
    };

    Platform.prototype.isNonOauthLogin = function () {
        var nonOauthPlatforms = [
                '<removed>'
            ],
            _this = this;
        return nonOauthPlatforms.some(function (elem) {
            return _this.name == elem;
        })
    };

    Platform.prototype.getPlatformLogins = function () {
        var self = this;
        return WebAPI.getPlatformLogins(self.name).then(function (resp) {
            self.logins = resp.data;
        });
    };

    Platform.prototype.getPlatformAccounts = function (filters) {
        var self = this,
            data = filters;
        data.type = self.name;
        return WebAPI.getPlatformAccounts(data).then(function (resp) {
            self.accounts = resp.data.items;
            self.accountsPageCount = resp.data._meta.pageCount;
            self.accountsPerPage = resp.data._meta.perPage;
            self.accountsTotalCount = resp.data._meta.totalCount;
        });
    };

    Platform.STATUS = {
        CONNECTED: "1",
        DISCONNECTED: "0"
    };

    return Platform;
};

app.factory('PlatformModel', app.factories.PlatformModel);