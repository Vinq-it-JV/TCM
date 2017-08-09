/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Sensorlog.service
 * @description
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Sensorlog', ['$rootScope', '$translate', '$timeout',
        function ($rootScope, $translate, $timeout) {

            var d_sensor = [];

            var chartConfig = {
                options: {
                    chart: {
                        type: 'line',
                        zoomType: 'x',
                        backgroundColor: null
                    }
                },
                title: {
                    text: null
                },
                loading: false,
                useHighStocks: false,
                series: [{
                    name: '',
                    showInLegend: false,
                    enableMouseTracking: true,
                    dataLabels: {
                        enabled: true
                    },
                    data: []
                }],
                yAxis: {
                    min: 0,
                    max: 0,
                    title: {
                        text: ''
                    },
                    plotLines: [{
                        value: 0,
                        color: '#55BF3B',
                        dashStyle: 'solid',
                        width: 2,
                        label: {
                            text: ''
                        }
                    }, {
                        value: 0,
                        color: '#DF5353',
                        dashStyle: 'solid',
                        width: 2,
                        label: {
                            text: ''
                        }
                    }]
                },
                xAxis: {
                    tickInterval: 1,
                    labels: {
                        formatter: function () {
                            var times = '';
                            if (angular.isArray(chartConfig.series[0].data))
                                if (chartConfig.series[0].data.length)
                                    times = chartConfig.series[0].data[this.value][0];
                            return times;
                        },
                        rotation: 90
                    },
                    title: {
                        text: ''
                    }
                },
                size: {
                    height: 250
                },
                func: function (chart) {
                    $timeout(function () {
                        chart.reflow();
                    }, 0);
                }
            };

            function recordOnIndex(record_id) {
                return record_id;
            }

            function isValidObject(object) {
                if (typeof object !== 'object')
                    return false;
                if (object.length === 0)
                    return false;
                if (Object.keys(object).length === 0)
                    return false;
                return true;
            }

            return {
                sensorlogDS: function () {
                    return this;
                },
                sensorSet: function (sensor) {
                    d_sensor = angular.copy(sensor);
                    return d_sensor;
                },
                sensor: function () {
                    return d_sensor;
                },
                logdataSet: function (data) {
                    this.logdataClear();
                    if (isValidObject(d_sensor)) {
                        switch (d_sensor.TypeId) {
                            case 4:
                                chartConfig.series[0].name = $translate.instant('SENSOR.INPUT');
                                chartConfig.yAxis.title.text = '';
                                chartConfig.yAxis.min = 0;
                                chartConfig.yAxis.max = 1;
                                chartConfig.yAxis.plotLines[0].value = 0;
                                chartConfig.yAxis.plotLines[1].value = 1;
                                break;
                            case 3:
                                chartConfig.series[0].name = $translate.instant('SENSOR.TEMPERATURE');
                                chartConfig.yAxis.title.text = '°C';
                                chartConfig.yAxis.min = Number(d_sensor.LowLimit) - 10;
                                chartConfig.yAxis.max = Number(d_sensor.HighLimit) + 10;
                                chartConfig.yAxis.plotLines[0].value = d_sensor.LowLimit;
                                chartConfig.yAxis.plotLines[1].value = d_sensor.HighLimit;
                                break;
                            default:
                                break;
                        }
                    }
                    if (typeof data == 'undefined')
                        return data;
                    chartConfig.xAxis.title.text = $translate.instant('SENSOR.TIME');
                    chartConfig.series[0].data = angular.copy(data);
                    return data;
                },
                logdata: function () {
                    return chartConfig.series[0].data;
                },
                logdataClear: function () {
                    chartConfig.series[0].data = [];
                },
                getChartConfig: function () {
                    return chartConfig;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);