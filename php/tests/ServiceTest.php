<?php

use Joc4enRatlla\Exceptions\NoViewException;
use Joc4enRatlla\Services\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * Prova que la funció loadView carrega correctament una vista amb les dades proporcionades.
     */
    public function testLoadView()
    {
        // Mock del directori de vistes i la inclusió de fitxers
        $viewFile = $_SERVER['DOCUMENT_ROOT'] . '/../Views/testview.view.php';

        // Crea un fitxer de vista temporal per a la prova
        file_put_contents($viewFile, '<?php echo $message; ?>');

        // Captura la sortida de la vista carregada
        ob_start();
        Service::loadView('testview', ['message' => 'Hola, món!']);
        $output = ob_get_clean();

        // Comprova que la sortida de la vista és correcta
        $this->assertEquals('Hola, món!', $output);

        // Neteja eliminant el fitxer temporal de la vista
        unlink($viewFile);
    }

    /**
     * Prova que la funció loadView carrega correctament una vista amb una ruta composta.
     */
    public function testLoadViewWithNestedPath()
    {
        // Mock del directori de vistes i la inclusió de fitxers en subdirectoris
        $viewFile = $_SERVER['DOCUMENT_ROOT'] . '/../Views/nested/testview.view.php';

        // Crea el directori i el fitxer de vista temporal per a la prova
        if (!is_dir(dirname($viewFile))) {
            mkdir(dirname($viewFile), 0777, true);
        }
        file_put_contents($viewFile, '<?php echo $message; ?>');

        // Captura la sortida de la vista carregada
        ob_start();
        Service::loadView('nested.testview', ['message' => 'Vista en subdirectori!']);
        $output = ob_get_clean();

        // Comprova que la sortida de la vista és correcta
        $this->assertEquals('Vista en subdirectori!', $output);

        // Neteja eliminant el fitxer i directori temporals
        unlink($viewFile);
        rmdir(dirname($viewFile));
    }

    /**
     * Prova que la funció loadView gestiona l'absència de la vista correctament (fitxer no trobat).
     */
    public function testLoadViewWithNonexistentView()
    {
        // Captura l'error que es generaria al no trobar la vista
        $this->expectException(NoViewException::class);


        // Intenta carregar una vista inexistent
        Service::loadView('inexistent.view', ['message' => 'Aquest fitxer no existeix.']);
    }
}
