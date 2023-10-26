$(document).ready(function () {
    ("use strict"); // Start of use strict

    getWordCountChart();
    getPieChart();
    function getPieChart() {
        //pie chart
        var ctx = document.getElementById("pieChart");
        var data = [];
        var config = {
            type: "pie",
            data: {
                datasets: [
                    {
                        data: data,
                        backgroundColor: ["#322f30", "#8de362", "#16994a"],
                        hoverBackgroundColor: ["#020202", "#389b07", "#048136"],
                    },
                ],
                labels: [
                    "Image Generate",
                    "Image Editor",
                    "Document Generator",
                ],
            },
            options: {
                legend: false,
                responsive: true,
            },
        };

        axios
            .get($("#pieChart").data("fetch"))
            .then((res) => {
                data.push(res.data.data.total_image_generated);
                data.push(res.data.data.total_image_edited);
                data.push(res.data.data.total_document);
                new Chart(ctx, config);
            })
            .catch((err) => {
                showAxiosErrors(err);
            });
        var myChart = new Chart(ctx);
    }

    //  Get Word Count Chart
    function getWordCountChart() {
        var ctx = document.getElementById("WordCount").getContext("2d");
        var temp_dataset = [];
        var rain_dataset = [];
        var chart_labels = [];
        var config = {
            type: "bar",
            data: {
                labels: chart_labels,
                datasets: [
                    {
                        type: "line",
                        label: "Total Words",
                        borderColor: "rgb(55, 160, 0)",
                        fill: false,
                        data: temp_dataset,
                    },
                    {
                        type: "bar",
                        label: "Total Words",
                        backgroundColor: "rgba(55, 160, 0, .1)",
                        borderColor: "rgba(55, 160, 0, .4)",
                        data: rain_dataset,
                    },
                ],
            },
            options: {
                legend: false,
                scales: {
                    yAxes: [
                        {
                            gridLines: {
                                color: "#e6e6e6",
                                zeroLineColor: "#e6e6e6",
                                borderDash: [2],
                                borderDashOffset: [2],
                                drawBorder: false,
                                drawTicks: false,
                            },
                            ticks: {
                                padding: 20,
                            },
                        },
                    ],

                    xAxes: [
                        {
                            maxBarThickness: 50,
                            gridLines: {
                                lineWidth: [0],
                            },
                            ticks: {
                                padding: 20,
                                fontSize: 14,
                                fontFamily: "'Nunito Sans', sans-serif",
                            },
                        },
                    ],
                },
            },
        };
        axios
            .get($("#WordCount").data("fetch"))
            .then((res) => {
                res.data.data.forEach((element) => {
                    var date = new Date(element.created_at);
                    var options = {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                    };
                    temp_dataset.push(element.total_words);
                    rain_dataset.push(element.total_words);
                    chart_labels.push(
                        date.toLocaleDateString("en-US", options)
                    );
                });

                new Chart(ctx, config);
            })
            .catch((err) => {
                showAxiosErrors(err);
            });
    }

    // Get Config for Chart
    function getConfig(chart_labels, temp_dataset, rain_dataset) {
        return;
    }
});
