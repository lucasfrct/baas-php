let input = document.querySelector("input[name='amount']")

input.addEventListener("keypress", (e) => {
    formatCurrency(e.target); 
})

input.addEventListener("keyup", (e) => {
    formatCurrency(e.target); 
})

function formatCurrency(input) {
    
    let cents = input.value.replace(/[\D]+/g,'');
    let currency = parseFloat(cents/100);
    input.value = currency.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

}