



function dibujarGraficoDeBarras(targetCanvas, dataSet, aditionalParams) {

    let labels = dataSet.labels;
    let datos = dataSet.datos;
    let labelX = dataSet.labelX;
    let labelY = dataSet.labelY;
    let title = dataSet.title;
    let generalLabel = dataSet.generalLabel;
    /**Parametros adicionales */
    let tipoDeGrafico = "bar";

    if (aditionalParams != undefined) {
        if ("type" in aditionalParams) tipoDeGrafico = aditionalParams.type;
    }
    /**Objetos */

    let BACKGROUNDS = [
        'rgba(255, 99, 132)',
        'rgba(54, 162, 235)',
        'rgba(255, 206, 86)',
        'rgba(75, 192, 192)',
        'rgba(153, 102, 255)'

    ];
    let BORDERS = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)'
    ];

    var myChart = new Chart(targetCanvas, {
        type: tipoDeGrafico,

        data: {
            labels: labels,

            datasets: [{

                label: generalLabel,
                data: datos,
                backgroundColor: BACKGROUNDS,
                borderColor: BORDERS,
                borderWidth: 1
            }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    title: {
                        display: true,
                        text: labelY,
                        color: 'black',
                        font: {
                            style: 'bold'
                        }
                    },
                    ticks: {
                        color: 'black',
                        font: {
                            style: 'bold'
                        },
                        callback: function (value, index, values) {
                            return parseInt(value);
                        }
                    }
                },
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: labelX,
                        color: "#000000",
                        font: {
                            size: 14,
                            style: 'bold',
                            lineHeight: 1.2,

                        }
                    },

                    ticks: {
                        color: 'black',
                        font: {
                            style: 'bold'
                        }
                    }

                }
            },
            plugins: {

                title: {
                    display: true,
                    color: "#000000",
                    text: title,
                    font: {
                        size: 14,
                        style: 'bold'
                    }
                },
                decimation: {
                    enabled: false,
                    algorithm: 'min-max',
                }

            },
            responsive: true
        }
    });

    return myChart;
}





function dibujarGraficoTorta(targetCanvas, dataSet) {

    let labels = dataSet.labels;
    let datos = dataSet.datos;
    let labelX = dataSet.labelX;
    let labelY = dataSet.labelY;
    let title = dataSet.title;
    let generalLabel = dataSet.generalLabel;

    //Data
    const DATA_COUNT = labels.length;
    const NUMBER_CFG = { count: DATA_COUNT, min: 0, max: 100 };

    const data = {
        labels: labels,
        datasets: [
            {
                label: generalLabel,
                data: datos,
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(4, 250, 235)',
                    'rgba(255, 90, 86)',
                    'rgba(200, 192, 200)',
                    'rgba(140, 252, 205)'

                ],
            }
        ]
    };

    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    color: "#000000",
                    text: title,
                    font: {
                        size: 14,
                        style: 'bold'
                    }
                }
            }
        },
    };



    //Mostrar
    var myChart = new Chart(targetCanvas, config);
    return myChart;

}