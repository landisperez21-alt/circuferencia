/*document.addEventListener('DOMContentLoaded', function(){
    const formulario = document.getElementById('frmLogin');
    
    formulario.addEventListener('submit',function(e){
        e.preventDefault();

        const user = document.getElementById('user').value.trim();
        const pass = document.getElementById('pass').value.trim();

        if(user==="tito" && pass==="123"){        
            window.location.href="home.php";
        }else{
            alert("dato incorrectos");
        }
    });

 
}); */

function calcularCircunferencia() {
    const radioInput = document.getElementById('txtRadio');
    const areaInput = document.getElementById('txtA');
    const perimetroInput = document.getElementById('txtP');
 
    if (!radioInput || !areaInput || !perimetroInput) {
        return;
    }
 
    const radio = parseFloat(radioInput.value);
 
    if (isNaN(radio) || radio <= 0) {
        alert('Ingrese un radio válido mayor a cero');
        radioInput.focus();
        return;
    }
 
    areaInput.value = (Math.PI * radio ** 2).toFixed(2);
    perimetroInput.value = (2 * Math.PI * radio).toFixed(2);
}
 
// El calculo se ejecuta solo al presionar el boton Calcular
