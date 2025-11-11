function crearGrafico(idCanvas, tipo) {
    fetch(`../controllers/graficos_controller.php?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            //* se obtiene el contexto del canvas donde se dibujará el gráfico
            const lienzo = document.getElementById(idCanvas).getContext('2d');

            //* se crea el gráfico de barras
            new Chart(lienzo, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: `Número de ${tipo}`,
                        data: data.counts,
                        backgroundColor: 'rgba(34, 139, 34, 0.6)',
                        borderColor: 'rgba(34, 139, 34, 1)',
                        borderWidth: 1
                    }]
                },
                //* opciones para que se vea bien
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error al cargar datos:', error));
}

//* aquí se llaman las funciones para crear los gráficos
crearGrafico('graficoLibros', 'libros');
crearGrafico('graficoReservas', 'reservas');
crearGrafico('graficoUsuarios', 'usuarios');