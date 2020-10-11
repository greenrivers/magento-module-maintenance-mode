/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_MaintenanceMode
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'domReady!'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'GreenRivers_MaintenanceMode/system/config/toggle_switch',
            text: ko.observable('Yes'),
            isChecked: ko.observable(true)
        },

        /**
         * @inheritdoc
         */
        initialize: function (config) {
            this._super();
            this.isChecked(config.isChecked);
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    isChecked: ko.observable(true)
                });

            this.isChecked.subscribe(function (value) {
                this.text(value ? 'Yes' : 'No');
                this.toggleElements();
            }, this);

            return this;
        },

        toggleElements: function () {
            $('#row_maintenance_mode_cron_frontend tr:not(:first)').toggle();
        },

        /**
         * @returns {Number}
         */
        getValue: function () {
            return this.isChecked() | 0;
        }
    });
});
