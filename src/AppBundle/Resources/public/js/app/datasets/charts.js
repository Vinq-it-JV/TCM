/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Stores
 * @description
 *
 * ## TCM V2.0 Charts
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Charts', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_temperatureChart = {
                options: {
                    chart: {
                        type: 'gauge',
                        plotBackgroundColor: null,
                        plotBackgroundImage: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: '200px',
                        animation: {
                            duration: 2000
                        }
                    },
                    title: {
                        text: ''
                    },
                    pane: {
                        startAngle: -100,
                        endAngle: 100,
                        background: [{
                            backgroundColor: {
                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                stops: [
                                    [0, '#777'],
                                    [1, '#333']
                                ]
                            },
                            borderWidth: 0,
                            outerRadius: '109%'
                        }, {
                            backgroundColor: {
                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                stops: [
                                    [0, '#333'],
                                    [1, '#777']
                                ]
                            },
                            borderWidth: 1,
                            outerRadius: '107%'
                        }, {
                            // default background
                        }, {
                            backgroundColor: '#777',
                            borderWidth: 0,
                            outerRadius: '105%',
                            innerRadius: '103%'
                        }]
                    },
                    // the value axis
                    yAxis: {
                        min: -20,
                        max: 120,
                        minorTickInterval: 'auto',
                        minorTickWidth: 1,
                        minorTickLength: 10,
                        minorTickPosition: 'inside',
                        minorTickColor: '#999',
                        tickPixelInterval: 30,
                        tickWidth: 2,
                        tickPosition: 'inside',
                        tickLength: 15,
                        tickColor: '#666',
                        labels: {
                            step: 2,
                            rotation: 'auto'
                        },
                        title: {
                            text: ' °C'
                        },
                        plotBands: [{
                            from: 80,
                            to: 0,
                            color: '#55BF3B' // green
                        }, {
                            from: -20,
                            to: 0,
                            color: '#DDDF0D' // yellow
                        }, {
                            from: 80,
                            to: 120,
                            color: '#DF5353' // red
                        }]
                    }
                },
                series: [{
                    name: '',
                    data: [0],
                    tooltip: {
                        valueSuffix: ' °C'
                    }
                }]
            };

            var d_inputChart = {
                options: {
                    chart: {
                        type: 'gauge',
                        plotBackgroundColor: null,
                        plotBackgroundImage: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: '200px',
                        animation: {
                            duration: 2000
                        }
                    },
                    title: {
                        text: ''
                    },
                    pane: {
                        startAngle: -100,
                        endAngle: 100,
                        background: [{
                            backgroundColor: {
                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                stops: [
                                    [0, '#777'],
                                    [1, '#333']
                                ]
                            },
                            borderWidth: 0,
                            outerRadius: '109%'
                        }, {
                            backgroundColor: {
                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                stops: [
                                    [0, '#333'],
                                    [1, '#777']
                                ]
                            },
                            borderWidth: 1,
                            outerRadius: '107%'
                        }, {
                            // default background
                        }, {
                            backgroundColor: '#777',
                            borderWidth: 0,
                            outerRadius: '105%',
                            innerRadius: '103%'
                        }]
                    },
                    // the value axis
                    yAxis: {
                        min: 0,
                        max: 1,
                        minorTickInterval: 'auto',
                        minorTickWidth: 1,
                        minorTickLength: 10,
                        minorTickPosition: 'inside',
                        minorTickColor: '#999',
                        tickPixelInterval: 0.1,
                        tickWidth: 2,
                        tickPosition: 'inside',
                        tickLength: 15,
                        tickColor: '#666',
                        labels: {
                            step: 2,
                            rotation: 'auto'
                        },
                        title: {
                            text: '<i class="fa fa-sign-in fa-2x"></i>',
                            useHTML: true
                        },
                        plotBands: [{
                            from: 0,
                            to: 0.5,
                            color: '#DF5353' // red
                        }, {
                            from: 0.5,
                            to: 1,
                            color: '#55BF3B' // green
                        }]
                    }
                },
                series: [{
                    name: '',
                    data: [0],
                    tooltip: {
                        valueSuffix: ''
                    }
                }]
            };

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
                chartsDS: function () {
                    return this;
                },
                temperatureChartConfig: function () {
                    return d_temperatureChart;
                },
                inputChartConfig: function () {
                    return d_inputChart;
                },
                setSensorObject: function (sensor) {
                    switch (sensor.TypeId)
                    {
                        case 2:
                            sensor.chartConfig = angular.copy(d_temperatureChart);
                            this.updateSensorObject(sensor);
                            break;
                        case 3:
                            sensor.chartConfig = angular.copy(d_temperatureChart);
                            this.updateSensorObject(sensor);
                            break;
                        case 4:
                            sensor.chartConfig = angular.copy(d_inputChart);
                            this.updateSensorObject(sensor);
                            break;
                        default:
                            break;
                    }
                },
                updateSensorObject: function (sensor) {
                    switch (sensor.TypeId)
                    {
                        case 2:
                            sensor.chartConfig.series[0].name = $translate.instant('SENSOR.TEMPERATURE');
                            sensor.chartConfig.series[0].data[0] = this.makeDecimal(sensor.InternalTemperature, 2);
                            break;
                        case 3:
                            sensor.chartConfig.series[0].name = $translate.instant('SENSOR.TEMPERATURE');
                            sensor.chartConfig.series[0].data[0] = this.makeDecimal(sensor.Temperature, 2);
                            sensor.chartConfig.options.yAxis.plotBands[0].from = this.makeDecimal(sensor.LowLimit);
                            sensor.chartConfig.options.yAxis.plotBands[0].to = this.makeDecimal(sensor.HighLimit);
                            sensor.chartConfig.options.yAxis.plotBands[1].to = this.makeDecimal(sensor.LowLimit);
                            sensor.chartConfig.options.yAxis.plotBands[2].from = this.makeDecimal(sensor.HighLimit);
                            break;
                        case 4:
                            sensor.chartConfig.series[0].name = $translate.instant('SENSOR.INPUT');
                            sensor.chartConfig.series[0].data[0] = sensor.SwitchState ? 1 : 0;
                            break;
                        default:
                            break;
                    }
                },
                makeDecimal: function (value, digits) {
                    var val = Number(value);
                    val = val.toFixed(2);
                    val = Number(val);
                    return val;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);