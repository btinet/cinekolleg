<?php

namespace App\Controller;

use App\Entity\LessonDoc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;


/**
 * @Route("/tutorial/documents", name="lesson_doc_")
 */
class LessonDocController extends AbstractController
{

    /**
     * @Route("/{id}/download", name="download", methods={"GET"})
     */
    public function download(LessonDoc $doc, DownloadHandler $downloadHandler): StreamedResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $downloadHandler->downloadObject($doc,'documentFile');
    }

}
