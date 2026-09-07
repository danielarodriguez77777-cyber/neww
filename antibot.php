<?php
header('X-Robots-Tag: noindex, nofollow, noarchive');
session_start();

class Antibot {

    // Genera token y tiempo al cargar la página
    public static function init() {
        $_SESSION['antibot_token'] = bin2hex(random_bytes(16));
        $_SESSION['antibot_time']  = time();
    }

    // Devuelve los campos ocultos para poner en tu HTML
    public static function campos(): string {
        if (empty($_SESSION['antibot_token'])) {
            self::init();
        }
        // Campo trampa — CSS lo oculta, bots lo llenan
        // Token real — viaja oculto para verificar
        return '
            <input type="text" 
                name="website" 
                id="website" 
                value="" 
                autocomplete="off"
                tabindex="-1"
                style="position:absolute;left:-9999px;opacity:0;height:0;width:0;">
            <input type="hidden" 
                name="ab_token" 
                value="' . $_SESSION['antibot_token'] . '">
        ';
    }

    // Verifica si el request es bot o no
    // Devuelve true si es humano, false si es bot
    public static function esHumano(): bool {

        // 1. Campo trampa debe estar vacío
        if (!empty($_POST['website']) || !empty($_GET['website'])) {
            error_log("ANTIBOT: campo honeypot lleno");
            return false;
        }

        // 2. Token debe existir y coincidir
        $token = $_POST['ab_token'] ?? $_GET['ab_token'] ?? '';
        if (empty($token) || $token !== ($_SESSION['antibot_token'] ?? '')) {
            error_log("ANTIBOT: token inválido");
            return false;
        }

        // 3. Tiempo mínimo en página: 2 segundos
        // Bots hacen submit instantáneo
        $tiempo = time() - ($_SESSION['antibot_time'] ?? 0);
        if ($tiempo < 2) {
            error_log("ANTIBOT: muy rápido ($tiempo segundos)");
            return false;
        }

        // 4. Tiempo máximo: 30 minutos (sesión expirada)
        if ($tiempo > 1800) {
            error_log("ANTIBOT: sesión expirada ($tiempo segundos)");
            return false;
        }

        return true;
    }
}