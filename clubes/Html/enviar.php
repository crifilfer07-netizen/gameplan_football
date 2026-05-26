<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Só executa o envio se o formulário tiver sido submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Chamada dos arquivos locais
    require 'Php Mailer/Exception.php';
    require 'Php Mailer/PHPMailer.php';
    require 'Php Mailer/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        // Configurações do Servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gameplanfootball1@gmail.com';
        $mail->Password   = 'zanjzfhqqrdwnudi'; // Lembra-te de colar aqui a senha da Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Quem está a enviar (O seu sistema)
        $mail->setFrom('gameplanfootball1@gmail.com', 'Sistema PAP');
        
        // Quem recebe (O seu e-mail do projeto)
        $mail->addAddress('gameplanfootball1@gmail.com');

        // Capturar os dados do formulário e guardar em variáveis
        $nome     = $_POST['name'];
        $email    = $_POST['email'];
        $telefone = $_POST['phone'];
        $assunto  = $_POST['subject'];
        $mensagem = $_POST['message'];

        // Conteúdo do E-mail
        $mail->isHTML(true);
        
        // O assunto do e-mail passa a ser o que o utilizador escolheu no formulário
        $mail->Subject = "Formulário PAP: " . $assunto; 
        
        // Corpo do e-mail estruturado com HTML para ficar organizado
        $mail->Body    = "
            <h3>Novo contacto recebido através do site:</h3>
            <p><b>Nome Completo:</b> {$nome}</p>
            <p><b>E-mail do Cliente:</b> {$email}</p>
            <p><b>Telefone:</b> {$telefone}</p>
            <p><b>Assunto Selecionado:</b> {$assunto}</p>
            <p><b>Mensagem:</b><br>{$mensagem}</p>
        ";

        // Enviar o e-mail
        $mail->send();
        echo 'Mensagem enviada com sucesso!';
        
    } catch (Exception $e) {
        echo "Erro no envio: {$mail->ErrorInfo}";
    }

} else {
    // Se alguém tentar aceder a este ficheiro diretamente, é mandado de volta para o formulário
    header("Location: contacto.html"); // Substitui pelo nome correto do teu ficheiro HTML
    exit;
}
?>