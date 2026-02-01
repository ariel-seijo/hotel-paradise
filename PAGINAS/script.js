document.getElementById('cantidadTurnos').addEventListener('input', function () {
  const cantidadTurnos = parseInt(this.value);
  const turnosContainer = document.getElementById('turnosContainer');
  turnosContainer.innerHTML = '';

  for (let i = 1; i <= cantidadTurnos; i++) {
    const label = document.createElement('label');
    label.textContent = `Horario ${i}`;
    const input = document.createElement('input');
    input.type = 'time';
    input.name = `horario_${i}`;
    input.classList.add('form-control', 'mb-2');

    turnosContainer.appendChild(label);
    turnosContainer.appendChild(input);
  }
});
