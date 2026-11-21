// mostrar u ocultar la contraseña con el ojito
const verClave = document.getElementById('verClave');

if (verClave) {
    verClave.addEventListener('click', () => {
        const campo = document.getElementById('password');
        const oculta = campo.type === 'password';

        campo.type = oculta ? 'text' : 'password';
        document.getElementById('ojoAbierto').classList.toggle('d-none', oculta);
        document.getElementById('ojoCerrado').classList.toggle('d-none', !oculta);
        verClave.setAttribute('aria-label', oculta ? 'Ocultar contraseña' : 'Mostrar contraseña');
    });
}