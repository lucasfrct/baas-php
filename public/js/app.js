let input = document.querySelector("input[name='amount']")

input.addEventListener("keyup", () => {
    // formatCurrency(this);
    console.log("input key")

})

function formatCurrency(input) {

    let value = input.value;
    value = value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    input.value(value)
    console.log("input format", value)

}