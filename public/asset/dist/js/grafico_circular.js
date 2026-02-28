document.addEventListener('DOMContentLoaded', function() {

    // Busca el elemento canvas donde se va a dibujar el gráfico
    const canvas = document.getElementById('grafico_circular');

    // Si el canvas no existe en la página, corta la ejecución
    if (!canvas) {
        console.log('Canvas no encontrado en esta página');
        return;
    }

    // Realiza una petición HTTP al backend para obtener los datos del gráfico
    fetch(URL_GRAFICO_CIRCULAR)

        // Verifica que la respuesta HTTP sea correcta (status 200–299)
        .then(response => {
            if (!response.ok) {
                // Si falla, lanza un error con el código de estado
                throw new Error('Error en la respuesta: ' + response.status);
            }
            // Convierte la respuesta a JSON
            return response.json();
        })

        // Procesa los datos recibidos del backend
        .then(res => {

            // Muestra los datos recibidos en consola (debug)
            console.log('Datos recibidos:', res);

            // Valida que la respuesta tenga el formato esperado
            if (!res.status || !res.data) {
                throw new Error('Datos inválidos');
            }

            // Convierte los ingresos a número
            // Si vienen null o vacíos, se usa 0
            const ingresos = parseFloat(res.data.ingresos) || 0;

            // Convierte los egresos a número
            const egresos = parseFloat(res.data.egresos) || 0;

            // Obtiene el contexto 2D del canvas
            const ctx = canvas.getContext('2d');

            // Crea el gráfico circular usando Chart.js
            new Chart(ctx, {

                // Tipo de gráfico
                type: 'pie',

                // Datos que se van a mostrar
                data: {
                    // Etiquetas del gráfico
                    labels: ['Ingresos', 'Egresos'],

                    // Conjunto de datos
                    datasets: [{
                        // Valores del gráfico
                        data: [ingresos, egresos],

                        // Colores de cada sección
                        backgroundColor: [
                            '#28a745', // verde (ingresos)
                            '#fd7e14'  // naranja/rojo (egresos)
                        ],

                        // Grosor del borde
                        borderWidth: 2,

                        // Color del borde
                        borderColor: '#fff'
                    }]
                },

                // Opciones de configuración del gráfico
                options: {

                    // El gráfico se adapta al tamaño del contenedor
                    responsive: true,

                    // Permite controlar el tamaño manualmente
                    maintainAspectRatio: false,

                    // Configuración de plugins
                    plugins: {

                        // Configuración de la leyenda
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#ffffff',
                                font: {
                                    size: 14
                                }
                            }
                        },

                        // Configuración del título
                        title: {
                            display: true,
                            text: 'Distribución de Caja',
                            color: '#ffffff',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },

                        // Configuración de los tooltips (cuando pasás el mouse)
                        tooltip: {
                            callbacks: {

                                // Personaliza el texto del tooltip
                                label: function(context) {

                                    // Valor de la sección actual
                                    let value = context.raw;

                                    // Suma total de ingresos + egresos
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);

                                    // Calcula el porcentaje
                                    let percentage = total > 0
                                        ? ((value / total) * 100).toFixed(1)
                                        : 0;

                                    // Texto final del tooltip
                                    return context.label + ': $' +
                                        value.toLocaleString('es-AR') +
                                        ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        })

        // Manejo de errores generales (fetch o datos inválidos)
        .catch(err => {

            // Muestra el error en consola
            console.error('Error cargando gráfico:', err);

            // Obtiene el contexto del canvas
            const ctx = canvas.getContext('2d');

            // Configuración del texto de error
            ctx.font = '16px Arial';
            ctx.fillStyle = '#dc3545';
            ctx.textAlign = 'center';

            // Muestra un mensaje de error dentro del canvas
            ctx.fillText(
                'Error al cargar datos',
                canvas.width / 2,
                canvas.height / 2
            );
        });
});
