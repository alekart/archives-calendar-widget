'use strict';

/**
 * @ngdoc overview
 * @name calendarEditorApp
 * @description
 * # calendarEditorApp
 *
 * Main module of the application.
 */
angular
    .module('calendarEditorApp', [


    ])
    .config(function () {

    }
);

/**
 * @ngdoc function
 * @name calendarEditorApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the calendarEditorApp
 */

angular.module('calendarEditorApp')
    .controller('editorCtrl', function ($scope, $timeout) {

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

            color: '#FFFFFF',
            fontSize: 15,

            background: '#990000',

            button: {
                width: '24px',
                color: '',
                border: '',
                borderRadius: '',
                borderWidth: 1,
                background: 'none',
                hover: {
                    background: 'black',
                    color: 'white'
                },
                disabled: {
                    opacity: 40
                }
            },

            menu: {
                background: "green",
                hover: {
                    background: 'blue',
                    color: 'yellow'
                },
                selected: {
                    background: 'red',
                    color: 'blue'
                }
            }
        };

        $scope.years = {};


        $scope.$watch('navigation.border', function (newValue) {
            $scope.navigation.borderBottom =
                $scope.navigation.borderTop =
                    $scope.navigation.borderLeft = $scope.navigation.borderRight = newValue;
            $scope.updateStyle();
        });


        $scope.updateStyle = function () {
            $timeout(
                function () {
                    jQuery('#style').html(
                        jQuery('#css').text()
                    );
                }, 0
            );
        };


        $scope.updateStyle();

        $scope.$watch('navigation', function (newValue, oldValue) {
            $scope.updateStyle();
        }, true);
        $scope.$watch('common', function (newValue, oldValue) {
            $scope.updateStyle();
        }, true);

        $timeout(function () {
            jQuery('input.wp-color-picker').trigger('input');
        }, 2000);

    }
);