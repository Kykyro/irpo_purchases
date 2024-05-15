<?php

namespace App\Controller\API;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api")
 */
class ApiUGPSController extends AbstractController
{
    /**
     * @Route("/ugps", name="app_api_ugps")
     */
    public function getFeedbackUnviewed(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sheet_template = $this->getParameter('ugps_table_file');;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sheet_template);
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];
        for($i = 1; $i <= $sheet->getHighestRow(); $i++)
        {
            $value = $sheet->getCell("A$i").' '.$sheet->getCell("B$i");
            array_push($data, $value);
        }


        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($data, 'json');


        return new JsonResponse($jsonContent);
    }
}
