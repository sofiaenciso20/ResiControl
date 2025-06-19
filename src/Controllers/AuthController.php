
<?php
use App\Config\Database;
 
class AuthController {
    public function login($email, $password) {
        //nos permite consultar la conexion a la base de datos
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");//me permite ver el correo que digite de primeras
        $stmt->execute([$email]);//proteger el email 
        $user = $stmt->fetch(PDO::FETCH_ASSOC);//obtener el primer usuario de la consulta
        //verificar si el usuario existe y si la contraseña es correcta
        if ($user && password_verify($password, $user['contrasena'])) {
            return [
                'documento' => $user['documento'],
                'nombre' => $user['nombre'] . ' ' . $user['apellido'],
                'correo' => $user['correo'],
                'rol' => $user['id_rol']
            ];
        }
        return null;
    }
}
 