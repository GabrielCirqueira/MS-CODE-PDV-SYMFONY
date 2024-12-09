function formatCPF(cpf) {
    return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}
document.querySelectorAll('.cpf').forEach(cell => {
    const rawCpf = cell.textContent.trim();
    if (/^\d{11}$/.test(rawCpf)) {
        cell.textContent = formatCPF(rawCpf);
    }
});

function formatarValorBR(valor) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
}

 