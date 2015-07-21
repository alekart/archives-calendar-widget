'use strict';

/**
 * @ngdoc function
 * @name calendarEditorApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the calendarEditorApp
 */

angular.module('calendarEditorApp.controllers', []).
    controller('editorCtrl', function ($scope, $timeout) {


        $scope.common = {
            textColor: '#FFFFFF',
            linkColor: 'red',
            fontSize: '16px',
            preview: '#FFFFFF'
        };

        $scope.navigation = {
            height: 30,

            marginTop: 0,
            marginRight: 0,
            marginBottom: 0,
            marginLeft: 0,

            border: 1,
            borderTop: 1,
            borderRight: 1,
            borderBottom: 1,
            borderLeft: 1,

            borderColor: '',

            borderRadius: 3,

            color: '#990000',
            fontSize: 15,

            background: '#990000',

            button: {
                width: '24px',
                color: '',
                border: '',
                borderRadius: '',
                background: 'none',
                hover: {
                    background: 'black',
                    color: 'white'
                },
                disabled: {
                    opacity: 40
                }
            }
        };

        console.log($scope.border);



        $scope.updateStyle = function(){
            $timeout(
                function () {
                    jQuery('#style').html(
                        jQuery('#css').text()
                    );
                }, 0
            );
        };


        //$scope.$watch('navigation', function(newValue, oldValue) {
        //    //$scope.navigation.borderBottom =
        //    //    $scope.navigation.borderTop =
        //    //    $scope.navigation.borderLeft = $scope.navigation.borderRight = newValue;
        //    $scope.updateStyle();
        //}, true);


        $scope.updateStyle();

        $scope.$watch('navigation', function() {
            $scope.updateStyle();
        }, true);
        $scope.$watch('common', function() {
            $scope.updateStyle();
        }, true);
    }
);