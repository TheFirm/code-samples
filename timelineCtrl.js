'use strict';

app.controllers.TimelineCtrl = function ($scope, TimelineCollection, TimelineActivity, HelpersService,
                                         Modal, NotificationService, gettext, timeline_data, DatepickerService) {
    $scope.currentPage = 1;
    $scope.collection = timeline_data;

    $scope.pageChanged = function () {
        $scope.collection.getData($scope.currentPage);
    };

    $scope.removeActivity = function (activity, index) {
        Modal.confirm(gettext.getTextCatalog('Do you want to delete activity?')).then(function () {
            activity.remove().then(function (resp) {
                NotificationService.addSuccessNotification(gettext.getTextCatalog('Activity was deleted successfully.'));
                $scope.pageChanged();
            }, HelpersService.serverErrorHandle);
        });
    };

    $scope.edit = function (item) {
        var dataForModal = item.initModalEdit();
        Modal.open('editTimelineActivity', dataForModal).then(function (modalData) {
            item.edit(modalData).then(function (resp) {
                NotificationService.addSuccessNotification(gettext.getTextCatalog('Activity was updated successfully'));
            }, HelpersService.serverErrorHandle);
        });
    };


    $scope.addActivity = function () {
        var dataForModal = {
            modalType: 'Add',
            datepicker: DatepickerService.datepicker(),
            setType: function (type) {
                this.type = type;
            },
            types: TimelineActivity.getAllTypes()
        };
        Modal.open('editTimelineActivity', dataForModal).then(function (modalData) {
            $scope.collection.addActivity(modalData).then(function (resp) {
                NotificationService.addSuccessNotification(gettext.getTextCatalog('Activity was created successfully'));
                $scope.pageChanged();
            }, HelpersService.serverErrorHandle);
        });
    };
};

app.controller('TimelineCtrl', app.controllers.TimelineCtrl);