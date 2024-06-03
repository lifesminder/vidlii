<div style="margin-bottom:0px;overflow: hidden">
    <div style="overflow: hidden; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ccc">
        <div style="float: left">
            <select style="padding: 6px 14px; font-size: 15px; width: 250px" onchange="if (this.value != 0) { window.location = '/analytics?page=videos&time=<?= $Date ?>&video='+this.value; }">
                <option value="0">Select a Video</option>
                <? foreach ($Videos as $Video) : ?>
                <option value="<?= $Video["url"] ?>"<? if (isset($_GET["video"]) && $_GET["video"] == $Video["url"]) : ?> selected<? endif ?>><?= $Video["title"] ?></option>
                <? endforeach ?>
            </select>
        </div>
        <div style="float: right">
            <select style="padding: 6px 14px; font-size: 15px" onchange="window.location = '/analytics?time='+this.value<? if (isset($_GET["page"])) : ?>+'&page=<?= $_GET["page"] ?><? if (isset($_GET["type"])) : ?>&type=<?= $_GET["type"] ?><? endif ?><? if (isset($_GET["country"])) : ?>&country=<?= $_GET["country"] ?><? endif ?><? if (isset($_GET["video"])) : ?>&video=<?= $_GET["video"] ?><? endif ?>'<? endif ?>">
                <option value="0"<? if (isset($_GET["time"]) && $_GET["time"] == 0) : ?> selected<? endif ?>>All Time</option>
                <option value="3"<? if (isset($_GET["time"]) && $_GET["time"] == 3) : ?> selected<? endif ?>>This Year</option>
                <option value="1"<? if (isset($_GET["time"]) && $_GET["time"] == 1) : ?> selected<? endif ?>>This Month</option>
                <option value="2"<? if (isset($_GET["time"]) && $_GET["time"] == 2) : ?> selected<? endif ?>>This Week</option>
                <option value="4"<? if (isset($_GET["time"]) && $_GET["time"] == 4) : ?> selected<? endif ?>>Today</option>
            </select>
        </div>
    </div>
    <? if ($Analytics_Page == "Default") : ?>
    <div style="overflow: hidden">
        <div style="float: left; width: 50%; box-sizing: border-box">
            <? if ($Popular_Videos) : ?>
            <div style="font-weight: bold; margin-bottom: 5px">Most Viewed Videos:</div>
            <? foreach ($Popular_Videos as $Video) : ?>
                <div style="border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-bottom: 3px; overflow: hidden">
                    <img <?= $Video["thumbnail"] ?> style="width: 33px; height: 24px; display: block; float: left; margin-right: 6px">
                    <div style="float:left; position: relative; top: 4px; width: 200px; height:16px; overflow: hidden"><a href="/analytics?page=videos&video=<?= $Video["url"] ?>"><?= $Video["title"] ?></a></div>
                    <div style="position: relative; top: 4px; border-left: 1px solid #ccc;float:right; padding:0 10px"><?= number_format($Video["day_views"]) ?></div>
                </div>
            <? endforeach ?>
            <? if (count($Popular_Videos) > 0) : ?><div style="text-align:center;margin-top:5px"><a href="/analytics?page=videos">Show All</a></div><? endif ?>
            <? else : ?>
                <div style="text-align:center;padding: 25px 0">No Videos found</div>
            <? endif ?>
        </div>
        <div style="float:left; width: calc(50% - 10px); box-sizing: border-box; padding-left: 10px; margin-left: 10px; border-left: 1px solid #ccc">
            <? if ($Subscribers) : ?>
            <div style="font-weight: bold; margin-bottom: 5px">Your Subscribers:</div>
            <? foreach ($Subscribers as $Subscriber) : ?>
            <div style="border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-bottom: 3px; overflow: hidden">
                <img src="https://www.countryflags.io/<?= $Subscriber["country"] ?>/flat/64.png" style="width: 24px;display:block; float: left; margin-right: 6px">
                <div style="float:left; position: relative; top: 4px; width: 200px; overflow: hidden"><a href="/analytics?page=subscribers&country=<?= $Subscriber["country"] ?>"><?= $Countries[$Subscriber["country"]] ?></a></div>
                <div style="position: relative; top: 4px; border-left: 1px solid #ccc;float:right; padding:0 10px"><?= number_format($Subscriber["amount"]) ?></div>
            </div>
            <? endforeach ?>
            <? if (count($Subscribers) > 0) : ?><div style="text-align:center;margin-top:5px"><a href="/analytics?page=subscribers<? if (isset($_GET["time"])) : ?>&time=<?= $_GET["time"] ?><? endif ?>">Show All</a></div><? endif ?>
            <? else : ?>
                <div style="text-align:center;padding: 25px 0">No Subscribers found</div>
            <? endif ?>
        </div>
    </div>
    <? if ($Watchtime_Per_Day) : ?>
    <div style="overflow:hidden">
        <div style="overflow:hidden;float:left;width:100%;margin:25px 0 10px 0;padding:10px 10px 10px 10px;height:202px;box-sizing:border-box;border:1px solid #ccc;border-radius: 4px">
            <div style="float:left; width: 200px">
                <div style="font-weight: bold;position:relative;top:6px">Watch Time</div>
                <div style="font-size:23px;position:relative;top:121px">
                    <?
                        $Count = 0;

                        foreach ($Watchtime_Per_Day as $Watchtime) {

                            $Count += round($Watchtime["amount"]);

                        }
                    ?>
                    <?= number_format($Count) ?> <span style="font-size:19px"> minutes</span>
                </div>
            </div>
            <div style="float:left;width:42%">
                <style>
                    div [title="JavaScript charts"] {
                        display: none !important;
                    }

                    g[transform="translate(70,180)"] {
                        display: none !important;
                    }
                </style>
                <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
                <script src="https://www.amcharts.com/lib/3/pie.js"></script>
                <script src="https://www.amcharts.com/lib/3/serial.js"></script>
                <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
                <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
                <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
                <style>
                    #chartdiv {
                        width	: 175%;
                        height	: 280px;
                    }
                </style>
                <script>
                    var chart = AmCharts.makeChart("chartdiv", {
                        "type": "serial",
                        "theme": "light",
                        "marginRight": 10,
                        "marginLeft": 10,
                        "autoMarginOffset": 20,
                        "mouseWheelZoomEnabled":true,
                        "dataDateFormat": "YYYY-MM-DD",
                        "valueAxes": [{
                            "id": "v1",
                            "axisAlpha": 0,
                            "position": "left",
                            "ignoreAxisWidth":true
                        }],
                        "balloon": {
                            "borderThickness": 1,
                            "shadowAlpha": 0
                        },
                        "graphs": [{
                            "id": "g1",
                            "balloon":{
                                "drop":true,
                                "adjustBorderColor":false,
                                "color":"#ffffff"
                            },
                            "bullet": "round",
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "bulletSize": 5,
                            "hideBulletsCount": 50,
                            "lineThickness": 2,
                            "title": "red line",
                            "useLineColorForBulletBorder": true,
                            "valueField": "value",
                            "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                        }],
                        "chartScrollbar": {
                            "graph": "g1",
                            "oppositeAxis":false,
                            "offset":30,
                            "scrollbarHeight": 80,
                            "backgroundAlpha": 0,
                            "selectedBackgroundAlpha": 0.1,
                            "selectedBackgroundColor": "#888888",
                            "graphFillAlpha": 0,
                            "graphLineAlpha": 0.5,
                            "selectedGraphFillAlpha": 0,
                            "selectedGraphLineAlpha": 1,
                            "autoGridCount":true,
                            "color":"#AAAAAA"
                        },
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "limitToGraph":"g1",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "valueScrollbar":{
                            "oppositeAxis":false,
                            "offset":50,
                            "scrollbarHeight":10
                        },
                        "categoryField": "date",
                        "categoryAxis": {
                            "parseDates": true,
                            "dashLength": 1,
                            "minorGridEnabled": true
                        },
                        "export": {
                            "enabled": false
                        },
                        "dataProvider": [
                            <? foreach ($Watchtime_Per_Day as $Video) : ?>
                            {
                                "date": "<?= $Video["submit_date"] ?>",
                                "value": <?= round($Video["amount"]) ?>
                            },
                            <? endforeach ?>
                        ]
                    });

                    chart.addListener("rendered", zoomChart);

                    zoomChart();

                    function zoomChart() {
                        chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
                    }
                </script>

                <!-- HTML -->
                <div id="chartdiv"></div>
            </div>
        </div>
    </div>
    <? endif ?>
    <div style="overflow:hidden;margin-top: 15px">
        <div style="overflow:hidden;float:left;width:32%;margin-right:2%;box-sizing:border-box;border:1px solid #ccc;border-radius: 4px">

            <div style="width:121px;padding:6px;border-right:1px solid #ccc;float:left;height:30px">
                <div style="position:relative;top:7px;text-align:center;">Views per Day</div>
            </div>
            <div style="float:left;font-size:20px;position:relative;top:10px;left:6px;font-weight:bold;width:39%;text-align:center"><? if ($Views_Per_Day) : ?>~<?= round($Views_Per_Day) ?><? else : ?>/<? endif ?></div>

        </div>
        <div style="overflow:hidden;margin-right:2%;float:left;width:32%;box-sizing:border-box;border:1px solid #ccc;border-radius: 4px">
            <div style="width:121px;padding:6px;border-right:1px solid #ccc;float:left;height:30px">
                <div style="position:relative;top:7px;text-align:center;">Average Rating</div>
            </div>
            <div style="float:left;font-size:20px;position:relative;top:10px;left:6px;font-weight:bold;width:39%;text-align:center"><? if ($Average_Rating) : ?><?= round($Average_Rating,2) ?> stars<? else : ?>/<? endif ?></div>

        </div>
        <div style="overflow:hidden;float:left;width:32%;box-sizing:border-box;border:1px solid #ccc;border-radius: 4px">
            <div style="width:121px;padding:6px;border-right:1px solid #ccc;float:left;height:30px">
                <div style="position:relative;top:7px;text-align:center;">Subscriber Age</div>
            </div>

            <div style="float:left;font-size:20px;position:relative;top:10px;left:6px;width:39%;text-align:center;font-weight:bold"><? if ($Average_Age) : ?>~<?= round($Average_Age["average"]) ?> years<? else : ?>/<? endif ?></div>
        </div>
    </div>
    <? elseif ($Analytics_Page == "Subscribers") : ?>
        <style>
            div [title="JavaScript charts"] {
                display: none !important;
            }
        </style>
        <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
        <script src="https://www.amcharts.com/lib/3/pie.js"></script>
        <script src="https://www.amcharts.com/lib/3/serial.js"></script>
        <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
        <style>
            #chartdiv {
                width	: 100%;
                height	: 400px;
            }
        </style>
        <div style="overflow: hidden">
            <div style="overflow:hidden;white-space: nowrap">
                <a href="/analytics?page=subscribers&time=<?= $Date ?>&type=0"><button style="float:left;box-sizing:border-box;border-bottom-right-radius: 0;border-top-right-radius: 0; width: 50%;">Subscribers per day</button></a>
                <a href="/analytics?page=subscribers&time=<?= $Date ?>&type=1"><button style="float: right;box-sizing:border-box;border-bottom-left-radius: 0;border-top-left-radius: 0; width: 50%;">Subscriber Sources</button></a>
            </div>
            <div style="overflow:hidden;margin-bottom: 15px">
                <? if (!isset($_GET["type"]) || $_GET["type"] == 0) : ?>
                <? if (count($Subscribers_Growth) > 0) : ?>
                <script>
                    var chart = AmCharts.makeChart("chartdiv", {
                        "type": "serial",
                        "theme": "light",
                        "marginRight": 10,
                        "marginLeft": 10,
                        "autoMarginOffset": 20,
                        "mouseWheelZoomEnabled":true,
                        "dataDateFormat": "YYYY-MM-DD",
                        "valueAxes": [{
                            "id": "v1",
                            "axisAlpha": 0,
                            "position": "left",
                            "ignoreAxisWidth":true
                        }],
                        "balloon": {
                            "borderThickness": 1,
                            "shadowAlpha": 0
                        },
                        "graphs": [{
                            "id": "g1",
                            "balloon":{
                                "drop":true,
                                "adjustBorderColor":false,
                                "color":"#ffffff"
                            },
                            "bullet": "round",
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "bulletSize": 5,
                            "hideBulletsCount": 50,
                            "lineThickness": 2,
                            "title": "red line",
                            "useLineColorForBulletBorder": true,
                            "valueField": "value",
                            "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                        }],
                        "chartScrollbar": {
                            "graph": "g1",
                            "oppositeAxis":false,
                            "offset":30,
                            "scrollbarHeight": 80,
                            "backgroundAlpha": 0,
                            "selectedBackgroundAlpha": 0.1,
                            "selectedBackgroundColor": "#888888",
                            "graphFillAlpha": 0,
                            "graphLineAlpha": 0.5,
                            "selectedGraphFillAlpha": 0,
                            "selectedGraphLineAlpha": 1,
                            "autoGridCount":true,
                            "color":"#AAAAAA"
                        },
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "limitToGraph":"g1",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "valueScrollbar":{
                            "oppositeAxis":false,
                            "offset":50,
                            "scrollbarHeight":10
                        },
                        "categoryField": "date",
                        "categoryAxis": {
                            "parseDates": true,
                            "dashLength": 1,
                            "minorGridEnabled": true
                        },
                        "export": {
                            "enabled": false
                        },
                        "dataProvider": [
                            <? foreach ($Subscribers_Growth as $Subscriber) : ?>
                            {
                                "date": "<?= $Subscriber["submit_date"] ?>",
                                "value": <?= $Subscriber["amount"] ?>
                            },
                            <? endforeach ?>
                        ]
                    });

                    chart.addListener("rendered", zoomChart);

                    zoomChart();

                    function zoomChart() {
                        chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
                    }
                </script>

                <!-- HTML -->
                <div id="chartdiv"></div>
                <? else : ?>
                <div style="text-align: center; font-size: 18px; padding: 50px 0">No Historic data was found</div>
                <? endif ?>
                <? else : ?>

                <? if (count($Subscribers) > 0) : ?>
                    <script>
                        var chart = AmCharts.makeChart( "chartdiv", {
                            "type": "pie",
                            "theme": "light",
                            "dataProvider": [
                                <? foreach ($Subscribers as $Subscriber) : ?>
                                {
                                    <?php
                                        if ($Subscriber["source"] != "c" && !empty($Subscriber["source"])) {
                                            $Video = $DB->execute("SELECT title FROM videos WHERE url = :URL", true, [":URL" => $Subscriber["source"]]);
                                        }
                                    ?>

                                    "Source": "<? if ($Subscriber["source"] == "") : ?>Unknown<? elseif ($Subscriber["source"] == "c") : ?>Your Channel<? else : ?><? if ($DB->RowNum == 1) : ?><?= $Video["title"] ?><? else : ?>Deleted Video<? endif ?><? endif ?>",
                                    "Amount": <?= $Subscriber["amount"] ?>
                                },
                                <? endforeach ?>
                                 ],
                            "valueField": "Amount",
                            "titleField": "Source",
                            "balloon":{
                                "fixedPosition":true
                            },
                            "export": {
                                "enabled": true
                            }
                        } );
                    </script>

                    <!-- HTML -->
                    <div id="chartdiv"></div>
                <? else : ?>
                    <div style="text-align: center; font-size: 18px; padding: 50px 0">No Historic data was found</div>
                <? endif ?>

                <? endif ?>
            </div>
            <? foreach ($Subscribers as $Subscriber) : ?>
                <div style="border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-bottom: 3px; overflow: hidden">
                    <? if (!isset($_GET["type"]) || $_GET["type"] == 0) : ?>
                        <img src="https://www.countryflags.io/<?= $Subscriber["country"] ?>/flat/64.png" style="width: 24px;display:block; float: left; margin-right: 6px">
                        <div style="float:left; position: relative; top: 4px; width: 200px; overflow: hidden"><a href="/analytics?page=subscribers&country=<?= $Subscriber["country"] ?>&time=<?= $Date ?>"><?= $Countries[$Subscriber["country"]] ?></a></div>
                        <div style="position: relative; top: 4px; border-left: 1px solid #ccc;float:right; padding:0 10px"><?= number_format($Subscriber["amount"]) ?></div>
                    <? else : ?>
                        <? if ($Subscriber["source"] == "") : ?>
                        <div style="float: left">Unknown Source</div>
                        <? elseif ($Subscriber["source"] == "c") : ?>
                        <div style="float:left">Your Channel</div>
                        <? else : ?>
                            <?php
                                $Video = $DB->execute("SELECT title, url FROM videos WHERE url = :URL", true, [":URL" => $Subscriber["source"]]);
                            ?>


                        <div style="float: left">
                            <? if ($DB->RowNum == 1) : ?>
                                <a href="/watch?v=<?= $Video["url"] ?>"><?= $Video["title"] ?></a>
                            <? else : ?>
                                Deleted Video
                            <? endif ?>
                        </div>
                        <? endif ?>
                        <div style="position: relative; top: 0px; border-left: 1px solid #ccc;float:right; padding:0 10px"><?= number_format($Subscriber["amount"]) ?></div>

                    <? endif ?>
                </div>
            <? endforeach ?>
        </div>
    <? elseif ($Analytics_Page == "Videos") : ?>
    <style>
        div [title="JavaScript charts"] {
            display: none !important;
        }
    </style>
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/pie.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <style>
        #chartdiv {
            width	: 100%;
            height	: 400px;
        }
    </style>
    <div style="overflow: hidden">
        <div style="overflow:hidden;margin-bottom: 15px">
            <? if (!isset($_GET["video"])) : ?>
                <? if (count($Videos_Growth) > 0) : ?>
                    <script>
                        var chart = AmCharts.makeChart("chartdiv", {
                            "type": "serial",
                            "theme": "light",
                            "marginRight": 10,
                            "marginLeft": 10,
                            "autoMarginOffset": 20,
                            "mouseWheelZoomEnabled":true,
                            "dataDateFormat": "YYYY-MM-DD",
                            "valueAxes": [{
                                "id": "v1",
                                "axisAlpha": 0,
                                "position": "left",
                                "ignoreAxisWidth":true
                            }],
                            "balloon": {
                                "borderThickness": 1,
                                "shadowAlpha": 0
                            },
                            "graphs": [{
                                "id": "g1",
                                "balloon":{
                                    "drop":true,
                                    "adjustBorderColor":false,
                                    "color":"#ffffff"
                                },
                                "bullet": "round",
                                "bulletBorderAlpha": 1,
                                "bulletColor": "#FFFFFF",
                                "bulletSize": 5,
                                "hideBulletsCount": 50,
                                "lineThickness": 2,
                                "title": "red line",
                                "useLineColorForBulletBorder": true,
                                "valueField": "value",
                                "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                            }],
                            "chartScrollbar": {
                                "graph": "g1",
                                "oppositeAxis":false,
                                "offset":30,
                                "scrollbarHeight": 80,
                                "backgroundAlpha": 0,
                                "selectedBackgroundAlpha": 0.1,
                                "selectedBackgroundColor": "#888888",
                                "graphFillAlpha": 0,
                                "graphLineAlpha": 0.5,
                                "selectedGraphFillAlpha": 0,
                                "selectedGraphLineAlpha": 1,
                                "autoGridCount":true,
                                "color":"#AAAAAA"
                            },
                            "chartCursor": {
                                "pan": true,
                                "valueLineEnabled": true,
                                "valueLineBalloonEnabled": true,
                                "cursorAlpha":1,
                                "cursorColor":"#258cbb",
                                "limitToGraph":"g1",
                                "valueLineAlpha":0.2,
                                "valueZoomable":true
                            },
                            "valueScrollbar":{
                                "oppositeAxis":false,
                                "offset":50,
                                "scrollbarHeight":10
                            },
                            "categoryField": "date",
                            "categoryAxis": {
                                "parseDates": true,
                                "dashLength": 1,
                                "minorGridEnabled": true
                            },
                            "export": {
                                "enabled": false
                            },
                            "dataProvider": [
                                <? foreach ($Videos_Growth as $Video) : ?>
                                {
                                    "date": "<?= $Video["submit_date"] ?>",
                                    "value": <?= $Video["amount"] ?>
                                },
                                <? endforeach ?>
                            ]
                        });

                        chart.addListener("rendered", zoomChart);

                        zoomChart();

                        function zoomChart() {
                            chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
                        }
                    </script>

                    <!-- HTML -->
                    <div id="chartdiv"></div>
                <? else : ?>
                    <div style="text-align: center; font-size: 18px; padding: 50px 0">No Historic data was found</div>
                <? endif ?>
            <? else : ?>
                <? if ($Videos_Growth) : ?>
                <script>
                    var chart = AmCharts.makeChart("chartdiv", {
                        "type": "serial",
                        "theme": "light",
                        "marginRight": 10,
                        "marginLeft": 10,
                        "autoMarginOffset": 20,
                        "mouseWheelZoomEnabled":true,
                        "dataDateFormat": "YYYY-MM-DD",
                        "valueAxes": [{
                            "id": "v1",
                            "axisAlpha": 0,
                            "position": "left",
                            "ignoreAxisWidth":true
                        }],
                        "balloon": {
                            "borderThickness": 1,
                            "shadowAlpha": 0
                        },
                        "graphs": [{
                            "id": "g1",
                            "balloon":{
                                "drop":true,
                                "adjustBorderColor":false,
                                "color":"#ffffff"
                            },
                            "bullet": "round",
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "bulletSize": 5,
                            "hideBulletsCount": 50,
                            "lineThickness": 2,
                            "title": "red line",
                            "useLineColorForBulletBorder": true,
                            "valueField": "value",
                            "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                        }],
                        "chartScrollbar": {
                            "graph": "g1",
                            "oppositeAxis":false,
                            "offset":30,
                            "scrollbarHeight": 80,
                            "backgroundAlpha": 0,
                            "selectedBackgroundAlpha": 0.1,
                            "selectedBackgroundColor": "#888888",
                            "graphFillAlpha": 0,
                            "graphLineAlpha": 0.5,
                            "selectedGraphFillAlpha": 0,
                            "selectedGraphLineAlpha": 1,
                            "autoGridCount":true,
                            "color":"#AAAAAA"
                        },
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "limitToGraph":"g1",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "valueScrollbar":{
                            "oppositeAxis":false,
                            "offset":50,
                            "scrollbarHeight":10
                        },
                        "categoryField": "date",
                        "categoryAxis": {
                            "parseDates": true,
                            "dashLength": 1,
                            "minorGridEnabled": true
                        },
                        "export": {
                            "enabled": false
                        },
                        "dataProvider": [
                            <? foreach ($Videos_Growth as $Video) : ?>
                            {
                                "date": "<?= $Video["submit_date"] ?>",
                                "value": <?= $Video["amount"] ?>
                            },
                            <? endforeach ?>
                        ]
                    });

                    chart.addListener("rendered", zoomChart);

                    zoomChart();

                    function zoomChart() {
                        chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
                    }
                </script>

                <!-- HTML -->
                <div id="chartdiv"></div>
            <? else : ?>
                <div style="text-align: center; font-size: 18px; padding: 50px 0">No Historic data was found</div>
            <? endif ?>
            <? endif ?>

        </div>
        <? if (!isset($_GET["video"])) : ?>
        <? foreach ($Popular_Videos as $Video) : ?>
            <div style="border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-bottom: 3px; overflow: hidden">
                <img <?= $Video["thumbnail"] ?> style="width: 33px; height: 24px; display: block; float: left; margin-right: 6px">
                <div style="float:left; position: relative; top: 4px; width: 200px; height:16px; overflow: hidden"><a href="/analytics?page=videos&video=<?= $Video["url"] ?>&time=<?= $Date ?>"><?= $Video["title"] ?></a></div>
                <div style="position: relative; top: 4px; border-left: 1px solid #ccc;float:right; padding:0 10px"><?= number_format($Video["day_views"]) ?></div>
            </div>
        <? endforeach ?>
        <? else : ?>
            <? if (count($Selected_Video) > 1 || $Selected_Video[0]["source"] != "") : ?>
            <? foreach ($Selected_Video as $Video) : ?>
                <? $Source = (string)$Video["source"]; $First_Char = substr($Source, 0, 1); ?>
                <div style="border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-bottom: 3px; overflow: hidden">
                    <div style="float: left">
                        <? if ($First_Char == "!") : ?>
                        <?php
                            $URL = str_replace("!", "", $Source);

                            $Related = $DB->execute("SELECT url, title FROM videos WHERE url = :URL", true, [":URL" => $URL]);
                        ?>
                        Related Videos: <? if ($DB->RowNum == 1) : ?><a href="/watch?v=<?= $Related["url"] ?>" style="font-weight:bold"><?= $Related["title"] ?><? else : ?>Deleted Video<? endif ?></a>
                        <? elseif ($First_Char == ")") : ?>
                        Search Term: <strong><?= htmlspecialchars(str_replace(")", "", $Source)) ?></strong>
                        <? elseif ($Source == "v") : ?>
                        Videos Page
                        <? elseif ($Source == "c") : ?>
                        Community Page
                        <? elseif ($Source == "h") : ?>
                        Homepage
                        <? elseif ($First_Char == "?") : ?>
                        Channel: <a href="/user/<?= str_replace("?", "", $Source) ?>" style="font-weight: bold"><?= str_replace("?", "", $Source) ?></a>
                        <? elseif (!empty($Source)) : ?>
                        External: <a href="<?= htmlspecialchars($Source, ENT_QUOTES) ?>" target="_blank" rel="nofollow" style="font-weight: bold"><?= htmlspecialchars($Source, ENT_QUOTES) ?></a>
                        <? else : ?>
                        Unknown Source
                        <? endif ?>
                    </div>
                    <div style="position: relative; top: 0px; border-left: 1px solid #ccc;float:right; padding:0 10px"><?= number_format($Video["amount"]) ?></div>
                </div>
            <? endforeach ?>
            <? endif ?>
        <? endif ?>
<? endif ?>
</div>
