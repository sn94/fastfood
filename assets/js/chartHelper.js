function dibujarGraficoDeBarras(targetCanvas, dataSet) {

    let labels = dataSet.labels;
    let datos = dataSet.datos;
    let labelX= dataSet.labelX;
    let labelY= dataSet.labelY;
    let title= dataSet.title;
    let generalLabel= dataSet.generalLabel;
    let myToolTips=  dataSet.tooltip;

  

    var myChart = new Chart(targetCanvas, {
        type: 'bar',
       
        data: {
            labels: labels,
          
            datasets: [{ 

                label: generalLabel,
                data: datos,
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)'

                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }
          ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,

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
                        }
                    }
                },
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: labelX,
                        font: {
                            size: 20,
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
                        size: 20,
                        style: 'bold'
                    }
                } ,
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