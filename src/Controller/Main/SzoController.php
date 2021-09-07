<?php


namespace App\Controller\Main;

use App\Entity\Database;
use App\Form\DatabaseType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DatabaseRepository;
use Symfony\Component\Security\Core\Security;

class SzoController extends AbstractController
{
    const ACTS_DIR = '/src/TemplatesXlsx/';
    const FONT_FILE = '/src/Fonts/timesi.ttf';
    const FONT_SIZE = 16;
    const ACTS_LIST = [
        'readiness' => [
            'title' => 'Акт готовности',
            'sheet_title' => 'Акт готовности',
            'cells' => [
                'spot_genitive' => ['N6'],
                'fio_genitive' => ['A8'],
                'ystaw' => ['J11'],
                'full_name_szo' => [
                    [
                        ['cell' => 'J28', 'width' => 465],
                        ['cell' => 'A30', 'width' => 745],
                        ['cell' => 'A31', 'width' => 745],
                    ]
                ],
                'adres' => [
                    [
                        ['cell' => 'I33', 'width' => 515],
                        ['cell' => 'A34', 'width' => 745]
                    ]
                ],
                'spot' => ['A56'],
                'short_name' => ['A57'],
                'short_fio' => ['H58'],
            ]
        ],
        'connection' => [
            'title' => 'Акт подключения',
            'sheet_title' => 'Акт подключения',
            'cells' => [
                'full_name_szo' => [
                    [
                        ['cell' => 'G11', 'width' => 650],
                        ['cell' => 'A13', 'width' => 885],
                        ['cell' => 'A14', 'width' => 885],
                    ]
                ],
                'adres' => ['A15'],
                'spot_fio_genitive' => ['I17'],
                'ystaw' => ['J20'],
                'spot' => ['P43'],
                'short_name' => ['P44'],
                'short_fio' => ['W46'],
            ]
        ],
        'transfer_router' => [
            'title' => 'Акт передачи роутера',
            'sheet_title' => 'Акт передачи роутера',
            'cells' => [
                'full_name_szo' => [
                    [
                        ['cell' => 'G12', 'width' => 600],
                        ['cell' => 'A14', 'width' => 765],
                        ['cell' => 'A15', 'width' => 765],
                    ]
                ],
                'adres' => ['A17'],
                'spot_genitive' => ['A20'],
                'fio_genitive' => ['A22'],
                'ystaw' => ['J24'],
                'spot' => ['P37'],
                'short_name' => ['P38'],
                'short_fio' => ['W39'],
            ]
        ],
        'service_prodiving_admin' => [
            'template' => 0,
            'protocol' => 1,
            'title' => 'Акт оказания услуг',
            'sheet_title' => 'Акт оказания услуг Админ',
            'cells' => [
                'full_name_szo' => [
                    [
                        ['cell' => 'A8', 'width' => 675],
                        ['cell' => 'A10', 'width' => 675],
                    ]
                ],
                'adres' => [
                    [
                        ['cell' => 'E11', 'width' => 430],
                        ['cell' => 'A12', 'width' => 675],
                    ]
                ],
                'spot_fio_genitive' => [
                    [
                        ['cell' => 'E15', 'width' => 430],
                        ['cell' => 'A17', 'width' => 675],
                    ]
                ],
                'spot' => ['G49'],
                'short_name' => ['G50'],
                'short_fio' => ['I53'],
            ]
        ],
        'service_prodiving_village' => [
            'template' => 1,
            'protocol' => 1,
            'title' => 'Акт оказания услуг',
            'sheet_title' => 'Акт оказания услуг СШ',
            'cells' => [
                'full_name_szo' => [
                    [
                        ['cell' => 'A8', 'width' => 675],
                        ['cell' => 'A10', 'width' => 675],
                    ]
                ],
                'adres' => [
                    [
                        ['cell' => 'E11', 'width' => 430],
                        ['cell' => 'A12', 'width' => 675],
                    ]
                ],
                'spot_fio_genitive' => [
                    [
                        ['cell' => 'E15', 'width' => 430],
                        ['cell' => 'A17', 'width' => 675],
                    ]
                ],
                'spot' => ['G49'],
                'short_name' => ['G50'],
                'short_fio' => ['I53'],
            ]
        ],
        'service_prodiving_city' => [
            'template' => 2,
            'protocol' => 1,
            'title' => 'Акт оказания услуг',
            'sheet_title' => 'Акт оказания услуг ГШ',
            'cells' => [
                'full_name_szo' => [
                    [
                        ['cell' => 'A8', 'width' => 675],
                        ['cell' => 'A10', 'width' => 675],
                    ]
                ],
                'adres' => [
                    [
                        ['cell' => 'E11', 'width' => 430],
                        ['cell' => 'A12', 'width' => 675],
                    ]
                ],
                'spot_fio_genitive' => [
                    [
                        ['cell' => 'E15', 'width' => 430],
                        ['cell' => 'A17', 'width' => 675],
                    ]
                ],
                'spot' => ['G49'],
                'short_name' => ['G50'],
                'short_fio' => ['I53'],
            ]
        ],
        'protocol1' => [
            'protocol' => 0,
            'title' => 'Протокол',
            'sheet_title' => 'Протокол',
            'cells' => [
                'adres' => [
                    'A4',
                    [
                        ['cell' => 'D18', 'width' => 465],
                        ['cell' => 'A19', 'width' => 755],
                    ],
                ],
                'full_name_szo' => [
                    [
                        ['cell' => 'C14', 'width' => 555],
                        ['cell' => 'A16', 'width' => 755],
                        ['cell' => 'A17', 'width' => 755],
                    ],
                    [
                        ['cell' => 'C47', 'width' => 555],
                        ['cell' => 'A48', 'width' => 755],
                    ],
                ],
                'spot_fio_genitive' => [
                    [
                        ['cell' => 'D22', 'width' => 465],
                        ['cell' => 'A24', 'width' => 755]
                    ]
                ],
                'spot' => ['A88'],
                'short_name' => ['A89'],
                'short_fio' => ['C92'],
            ]
        ],
        'protocol_fp' => [
            'protocol' => 1,
            'title' => 'Протокол',
            'sheet_title' => 'Протокол 2',
            'cells' => [
                'adres' => [
                    'A4',
                    [
                        ['cell' => 'D18', 'width' => 465],
                        ['cell' => 'A19', 'width' => 755],
                    ]
                ],
                'full_name_szo' => [
                    [
                        ['cell' => 'C14', 'width' => 555],
                        ['cell' => 'A16', 'width' => 755],
                        ['cell' => 'A17', 'width' => 755],
                    ],
                    [
                        ['cell' => 'C46', 'width' => 555],
                        ['cell' => 'C47', 'width' => 555],
                        ['cell' => 'C48', 'width' => 555],
                    ]
                ],
                'spot_fio_genitive' => ['D22'],
                'spot' => ['A87'],
                'short_name' => ['A88'],
                'short_fio' => ['C90'],
                'short_fio_reverse' => true,
                'spot_name_fp' => ['F96'],
                'main_fiofp' => ['G100'],
            ]
        ],
        'transfer_equipment' => [
            'title' => 'Акт передачи оборудования',
            'sheet_title' => 'Акт передачи оборудования',
            'cells' => [
                'full_name_szo' => [
                    [
                        ['cell' => 'G12', 'width' => 635],
                        ['cell' => 'A14', 'width' => 820]
                    ]
                ],
                'adres' => ['A17'],
                'spot_genitive' => ['A20'],
                'fio_genitive' => ['A22'],
                'ystaw' => ['J24'],
                'spot' => ['P42'],
                'short_name' => ['P43'],
                'short_fio' => ['W44'],
            ]
        ],
    ];

    /**
     * @param DatabaseRepository $docRepository
     * @param Request $request
     * @Route("/", name="home")
     * @return Response
     */
    public function index(DatabaseRepository $docRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('search_query', null);
        $page = $request->query->get('page', 1);
        $docList = $docRepository->findLatest($page, $searchQuery);
        return $this->render('main/index.html.twig', [
            'docList' => $docList,
            'searchQuery' => $searchQuery
        ]);
    }

    /**
     * @Route("/szo/{id}", name="show_szo", requirements={"id"="\d+"})
     */
    public function showSzo(Database $database): Response
    {
        return $this->render('main/show.html.twig', [
            'doc' => $database,
            'protocols' => Database::PROTOCOLS,
            'protocol_fp_id' => Database::PROTOCOL_FP_ID,
            'templates' => Database::TEMPLATES,
            'acts_list' => self::ACTS_LIST
        ]);
    }

    /**
     * @Route("/add_szo", name="add_szo", methods={"GET", "POST"})
     */
    public function addSzo(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $szo = new Database();
        $form = $this->createForm(DatabaseType::class, $szo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $szo->setAuthor($security->getUser());
            $szo->setCreatedAt(new \DateTime());
            $entityManager->persist($szo);
            $entityManager->flush();

            return $this->redirectToRoute('show_szo', ['id' => $szo->getId()]);
        }
        return $this->render('main/szo_form.html.twig', [
            'doc' => $szo,
            'form' => $form->createView(),
            'protocols' => Database::PROTOCOLS,
            'protocol_fp_id' => Database::PROTOCOL_FP_ID,
            'templates' => Database::TEMPLATES
        ]);
    }

    /**
     * @Route("/szo/{id}/edit", name="edit_szo", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function editSzo(Request $request, Database $szo, Security $security, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DatabaseType::class, $szo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $szo->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('show_szo', ['id' => $szo->getId()]);
        }
        return $this->render('main/szo_form.html.twig', [
            'doc' => $szo,
            'form' => $form->createView(),
            'protocols' => Database::PROTOCOLS,
            'protocol_fp_id' => Database::PROTOCOL_FP_ID,
            'templates' => Database::TEMPLATES
        ]);
    }

    /**
     * @Route("/szo/{id}/delete", name="delete_szo", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Database $szo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $szo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($szo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/szo/{id}/act_download", name="szo_act_download", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function actDownload(Request $request, Database $szo): Response
    {
        // С помощью этой функции и текстом, который занимает примерно всю ячейку, можно вычислить ширину ячейки
//        dd($stringPixelWidth = imagettfbbox(self::FONT_SIZE, 0, $this->getParameter('kernel.project_dir') . self::FONT_FILE, 'Республика Дагестан, Тляратинский район, с. Албания, ул. Центральная')[2]);
        $act = $request->query->get('act', null);
        if (empty($act)) {
            return $this->redirectToRoute('show_szo', ['id' => $szo->getId()]);
        } else {
            if (!isset(self::ACTS_LIST[$act]) && $act !== "all") {
                return $this->redirectToRoute('show_szo', ['id' => $szo->getId()]);
            } else {
                $fileName = "Лист";
                if ($act === "all") {
                    $neededActs = [];
                    foreach (self::ACTS_LIST as $act => $metadata) {
                        if (
                            (isset($metadata['protocol']) && $metadata['protocol'] === $szo->getProtocol() && !isset($metadata['template'])) ||
                            (isset($metadata['template']) && $metadata['template'] === $szo->getTemplate() && $metadata['protocol'] == $szo->getProtocol()) ||
                            (!isset($metadata['protocol']) && !isset($metadata['template']))
                        ) {
                            $neededActs[] = $act;
                            $fileName = "Все акты";
                        }
                    }
                } else {
                    $neededActs = [$act];
                    $fileName = self::ACTS_LIST[$act]['title'];
                }
                $clonedActSheets = [];
                foreach ($neededActs as $act) {
                    $actMetadata = self::ACTS_LIST[$act];
                    $actsFile = IOFactory::load($this->getParameter('kernel.project_dir') . self::ACTS_DIR . 'acts.xlsx');
//                    if ($actsFile->getSheetByName($actMetadata['sheet_title']) === null) {
//                        dd($act, $actMetadata);
//                    }
                    $clonedActSheet = clone $actsFile->getSheetByName($actMetadata['sheet_title']);
                    foreach ($actMetadata['cells'] as $field => $cells) {
                        if ($field === 'short_fio_reverse') continue;
                        foreach ($cells as $cell) {
                            $neededString = "";
                            switch ($field) {
                                case 'spot_genitive':
                                    $neededString = $szo->getSpotGenitive();
                                    break;
                                case 'fio_genitive':
                                    $neededString = $szo->getFioGenitive();
                                    break;
                                case 'ystaw':
                                    $neededString = $szo->getYstaw();
                                    break;
                                case 'full_name_szo':
                                    $neededString = $szo->getFullNameC30();
                                    break;
                                case 'adres':
                                    $neededString = $szo->getAdres();
                                    break;
                                case 'spot':
                                    $neededString = $szo->getSpot();
                                    break;
                                case 'short_name':
                                    $neededString = $szo->getShortName();
                                    break;
                                case 'short_fio':
                                    if (isset($actMetadata['cells']['short_fio_reverse'])) {
                                        $neededString = array_reduce(explode(" ", $szo->getFIO()), function ($string, $fioPart) {
                                            if (empty($string)) {
                                                return $fioPart . " ";
                                            } else {
                                                return $string . mb_substr($fioPart, 0, 1) . ".";
                                            }
                                        });
                                    } else {
                                        $surname = "";
                                        $neededString = implode("", array_reduce(explode(" ", $szo->getFIO()), function ($string, $fioPart) use (&$surname) {
                                                if (empty($surname)) {
                                                    $surname = $fioPart;
                                                    return [];
                                                } else {
                                                    array_push($string, mb_substr($fioPart, 0, 1) . ".");
                                                    return $string;
                                                }
                                            })) . " " . $surname;
                                    }
                                    break;
                                case 'spot_fio_genitive':
                                    $neededString = $szo->getSpotGenitive() . " " . $szo->getFioGenitive();
                                    break;
                                case 'spot_name_fp':
                                    $neededString = $szo->getSpotNameFP();
                                    break;
                                case 'main_fiofp':
                                    if (isset($actMetadata['cells']['short_fio_reverse'])) {
                                        $neededString = array_reduce(explode(" ", $szo->getMainFIOFP()), function ($string, $fioPart) {
                                            if (empty($string)) {
                                                return $fioPart . " ";
                                            } else {
                                                return $string . mb_substr($fioPart, 0, 1) . ".";
                                            }
                                        });
                                    } else {
                                        $surname = "";
                                        $neededString = implode("", array_reduce(explode(" ", $szo->getMainFIOFP()), function ($string, $fioPart) use (&$surname) {
                                                if (empty($surname)) {
                                                    $surname = $fioPart;
                                                    return [];
                                                } else {
                                                    array_push($string, mb_substr($fioPart, 0, 1) . ".");
                                                    return $string;
                                                }
                                            })) . " " . $surname;
                                    }
                                    break;
                            }
                            if (is_array($cell)) {
                                for ($i = 0; $i <= count($cell) - 1; $i++) {
                                    $stringPixelWidth = imagettfbbox(self::FONT_SIZE, 0, $this->getParameter('kernel.project_dir') . self::FONT_FILE, $neededString)[2];
                                    if ($i < count($cell) - 1 && $stringPixelWidth > $cell[$i]['width']) {
                                        $words = explode(" ", $neededString);
                                        $lastWords = [];
                                        do {
                                            $lastWord = array_pop($words);
                                            array_unshift($lastWords, $lastWord);
                                            $stringPixelWidth = imagettfbbox(self::FONT_SIZE, 0, $this->getParameter('kernel.project_dir') . self::FONT_FILE, implode(" ", $words))[2];
                                        } while ($stringPixelWidth > $cell[$i]['width']);
                                        $clonedActSheet->setCellValue($cell[$i]['cell'], implode(" ", $words));
                                        $neededString = implode(" ", $lastWords);
                                    } else {
                                        $clonedActSheet->setCellValue($cell[$i]['cell'], $neededString);
                                        break;
                                    }
                                }
                            } else {
                                $clonedActSheet->setCellValue($cell, $neededString);
                            }
                        }
                    }
                    $clonedActSheets[] = $clonedActSheet;
                }
                $newActFile = new Spreadsheet();
                $newActFile->removeSheetByIndex(0);
                foreach ($clonedActSheets as $clonedActSheet) {
                    $newActFile->addExternalSheet($clonedActSheet);
                }
                $writer = new Xlsx($newActFile);
                $tempFilename = $this->getParameter('kernel.project_dir') . self::ACTS_DIR . uniqid(microtime(true), true);
                $tempFile = tempnam(sys_get_temp_dir(), $tempFilename);
                $writer->save($tempFile);
                return $this->file($tempFile, $fileName . '.xlsx', ResponseHeaderBag::DISPOSITION_INLINE);
            }
        }
    }
}
