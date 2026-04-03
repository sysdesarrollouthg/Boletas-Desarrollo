function calcular_DigitoVerificador($icString) {
    // Serie de factores fijos: 7, 9, 3, 5 (se repiten cíclicamente)
    $factores = [7, 9, 3, 5];
    
    // Inicializar suma con el primer dígito (sin factor)
    $suma = (int) substr($icString, 0, 1);
    $longitud = strlen($icString);
    
    // Recorrer desde el segundo carácter hasta el final
    for ($i = 2; $i <= $longitud; $i++) {
        // Obtener el dígito actual (posición $i en Progress es 1-indexada)
        $digito = (int) substr($icString, $i - 1, 1);
        
        // Determinar el factor correspondiente: el índice en el array es $i % 4
        // (Esto equivale a ENTRY(i MODULO 4 + 1, cSerie) en Progress)
        $factor = $factores[$i % 4];
        
        // Acumular producto
        $suma += $digito * $factor;
    }
    
    // Calcular el dígito final: truncar (suma / 2) y luego módulo 10
    $resultado = intdiv($suma, 2) % 10;  // intdiv requiere PHP 7+, si no, usar (int)($suma / 2)
    
    return (string) $resultado;
}