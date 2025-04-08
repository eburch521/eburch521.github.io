// Retrieve the value from the cabbageAmount input field
const cabbageInput = document.getElementById('cabbage-amount');
const totalMix = document.getElementById('total-mix');
const carrotAmt = document.getElementById('carrot-amount');
const garlicAmt = document.getElementById('garlic-amount');
const gingerAmt = document.getElementById('ginger-amount');
const onionAmt = document.getElementById('onion-amount');
const tamariAmt = document.getElementById('tamari-amount');
const saltAmt = document.getElementById('salt-amount');
const sugarAmt = document.getElementById('sugar-amount');
const pepperAmt = document.getElementById('pepper-amount');
const submitBtn = document.getElementById('submit-btn');
const resetBtn = document.getElementById('reset-btn');
const resultContainer = document.getElementById('result-container');
const buttons = document.getElementById('buttons');
let counter = 1;


function kimchiCalc() {
  const cabbageValue = cabbageInput.value;
  const carrotValue = cabbageValue * .04;
  const garlicValue = cabbageValue * .06;
  const gingerValue = cabbageValue * .02; 
  const onionValue = cabbageValue * .04;
  const tamariValue = cabbageValue * .04;
  const saltValue = cabbageValue * .026;
  const sugarValue = cabbageValue * .085;
  const pepperValue = cabbageValue * .012;
  
  carrotAmt.value = carrotValue.toFixed(2);
  garlicAmt.value = garlicValue.toFixed(2);
  gingerAmt.value = gingerValue.toFixed(2);
  onionAmt.value = onionValue.toFixed(2);
  tamariAmt.value = tamariValue.toFixed(2);
  saltAmt.value = saltValue.toFixed(2);
  sugarAmt.value = sugarValue.toFixed(2);
  pepperAmt.value = pepperValue.toFixed(2);
  totalMix.value = (carrotValue + garlicValue + gingerValue + onionValue + sugarValue + pepperValue).toFixed(2);
}
function resetForm() {
  cabbageInput.value = '';
  carrotAmt.value = '';
  garlicAmt.value = '';
  gingerAmt.value = '';
  onionAmt.value = '';
  tamariAmt.value = '';
  saltAmt.value = '';
  sugarAmt.value = '';
  pepperAmt.value = '';
  totalMix.value = '';

  // Clear the result table
  const resultTable = document.getElementById('result-table');
  if (resultTable) {
    const tbody = resultTable.querySelector('tbody');
    if (tbody) {
      tbody.innerHTML = ''; // Clear all rows in the table body
    }
  }

  // Reset the counter
  counter = 1;
}

function displayResult() {
    let resultTable = document.getElementById('result-table');
    if (!resultTable) {
      resultTable = document.createElement('table');
      resultTable.id = 'result-table';
      resultTable.innerHTML = `
        <thead>
          <tr>
            <th>#</th>
            <th>Cabbage (g)</th>
            <th>Carrots (g)</th>
            <th>Garlic (g)</th>
            <th>Ginger (g)</th>
            <th>Red Onions (g)</th>
            <th>Tamari (g)</th>
            <th>Salt (g)</th>
            <th>Sugar (g)</th>
            <th>Pepper Flakes (g)</th>
            <th>Total Mix (g)</th>
          </tr>
        </thead>
        <tbody></tbody>
      `;
      resultContainer.appendChild(resultTable);
    }
  // Add a new row to the table
  const tbody = resultTable.querySelector('tbody');
  const newRow = document.createElement('tr');
  newRow.innerHTML = `
    <td>${counter}</td>
    <td>${cabbageInput.value}</td>
    <td>${carrotAmt.value}</td>
    <td>${garlicAmt.value}</td>
    <td>${gingerAmt.value}</td>
    <td>${onionAmt.value}</td>
    <td>${tamariAmt.value}</td>
    <td>${saltAmt.value}</td>
    <td>${sugarAmt.value}</td>
    <td>${pepperAmt.value}</td>
    <td>${totalMix.value}</td>
  `;
  tbody.appendChild(newRow);

  // Increment the counter for the next result
  counter++;
}
// Add an event listener to capture the value when it changes
cabbageInput.addEventListener('input', () => {
  kimchiCalc();
});
// Add an event listener to the submit button to capture the value when clicked
submitBtn.addEventListener('click', (event) => {
  event.preventDefault(); // Prevent form submission
  kimchiCalc();
  displayResult();
});
// Add event listener to enter key to submit the form
cabbageInput.addEventListener('keypress', (event) => {
  if (event.key === 'Enter') {
    event.preventDefault(); // Prevent form submission
    kimchiCalc();
    displayResult();
  }
});
// Add an event listener to the reset button to clear the input fields
resetBtn.addEventListener('click', () => {
  resetForm();
});

// Add an event listener to the export button
document.getElementById('export-btn').addEventListener('click', exportToCSV);

function exportToCSV() {
  const resultTable = document.getElementById('result-table');
  if (!resultTable) {
    alert('No results to export.');
    return;
  }

  const rows = resultTable.querySelectorAll('tr');
  const csvContent = Array.from(rows)
    .map(row =>
      Array.from(row.cells)
        .map(cell => `"${cell.textContent.replace(/"/g, '""')}"`) // Enclose in quotes and escape double quotes
        .join(',')
    )
    .join('\n');

  const blob = new Blob([csvContent], { type: 'text/csv' });

  // Get the current date and time
  const now = new Date();
  const timestamp = now.toISOString().replace(/[:.]/g, '-'); // Format: YYYY-MM-DDTHH-MM-SS

  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `kimchi_results_${timestamp}.csv`; // Include timestamp in the filename
  a.click();
  URL.revokeObjectURL(url);
}

