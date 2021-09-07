<?php
#https://medium.com/@biberogluyusuf/symfony-5-phpspreadsheet-creating-excel-file-f4ea14045ce5
namespace App\Controller\Admin;

use App\Entity\Record;
use App\Entity\Database;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class AdminRecordController extends AbstractController
{

    /**
     * @Route("/admin/user/add/record/refresh", name="admin_user_refresh")
     */
    public function refresh(Request $request)
    {

        $session = new Session();
        //$session->start();

        $test = $session->get('FIO');

        $form_record_test = $this->createFormBuilder()
            ->add('FIO', TextareaType::class, array('label' => 'ФИО  в родительном падеже', 'data' => $test['FIO']))
            ->add('ystaw', TextareaType::class, array('label' => 'Устав, приказ (на основании чего)', 'data' => $test['ystaw']))
            ->add('fullNameC30', TextareaType::class, array('label' => 'Полное наименование СЗО', 'data' => $test['fullNameC30']))
            ->add('adres', TextareaType::class, array('label' => 'Адрес', 'data' => $test['adres']))
            ->add('spot', TextareaType::class, array('label' => 'Должность в именительном падеже', 'data' => $test['spot']))
            ->add('shortName', TextareaType::class, array('label' => 'Сокращенное наименование СЗО', 'data' => $test['shortName']))
            ->add('spotNameFP', TextareaType::class, array('label' => 'Должность и наименование ФП', 'data' => $test['spotNameFP']))
            ->add('mainFIOFP', TextareaType::class, array('label' => 'ФИО заведующей ФП', 'data' => $test['mainFIOFP']))
            ->add('submit', SubmitType::class, array('label' => 'SAVE'))
            ->getForm();
        $form_record_test->handleRequest($request);

#print_r($test);

        if ($form_record_test->isSubmitted() && $form_record_test->isValid()) {
            $data = $form_record_test->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $product = new Database();
            $product->setFIO($data['FIO']);
            $product->setFullNameC30($data['ystaw']);
            $product->setShortName($data['fullNameC30']);
            $product->setSpot($data['adres']);
            $product->setAdres($data['spot']);
            $product->setYstaw($data['shortName']);
            $product->setSpotNameFP($data['spotNameFP']);
            $product->setMainFIOFP($data['mainFIOFP']);

            $entityManager->persist($product);

            $entityManager->flush();

#file
            $inputFileName = $this->getParameter('kernel.project_dir') . '/src/TemplatesXlsx/acts.xlsx';

            /** Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            # check if the file load was successful
            if (empty($spreadsheet) == false) {
                print_r(' sucses');
            }

//Получаем текущий активный лист
            $sheet = $spreadsheet->getActiveSheet("Акт готовности");

            $sheet->setCellValue('N6', $data['spot']); # Должность в родительном
            $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
            $sheet->setCellValue('J11', $test['ystaw']); #    устав
            $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
            $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
            $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
            $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование
            $sheet->setCellValue('H58', $test['FIO']); # ФИО сокращенное ???????????

            $sheet = $spreadsheet->getActiveSheet(2);

            $sheet->setCellValue('G11', $test['fullNameC30']); #полное наименование c30
            $sheet->setCellValue('A15', $test['adres']);   #  адрес
            $sheet->setCellValue('J20', $test['ystaw']); #    устав
            $sheet->setCellValue('P43', $data['spot']); # должность в именит пад ?????????????
            $sheet->setCellValue('P44', $test['shortName']);  # сокращенное наименование
            $sheet->setCellValue('W46', $test['FIO']); # ФИО сокращенное ???????????
            /*
                    $sheet = $spreadsheet->getActiveSheet("Протокол");

                    $sheet->setCellValue('A4', $test['adres']);   #   адрес
                    $sheet->setCellValue('C14', $test['fullNameC30']); # A16 полное наименование c30
                    $sheet->setCellValue('D18', $test['adres']);   #  А19 адрес
                    $sheet->setCellValue('D22', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A24', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('C47', $test['fullNameC30']); # A48 полное наименование c30
                    $sheet->setCellValue('A88', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A89', $test['shortName']);  # сокращенное наименование
                    $sheet->setCellValue('C92', $test['FIO']); # ФИО сокращенное ???????????

            */
            /*
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование
                    $sheet->setCellValue('H58', $test['FIO']); # ФИО сокращенное ???????????

                    $sheet = $spreadsheet->getActiveSheet('Протокол2');

                    $sheet->setCellValue('A4', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('J11', $test['ystaw']); #    устав
                    $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
                    $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование
                    $sheet->setCellValue('H58', $test['FIO']); # ФИО сокращенное ???????????

                    $sheet = $spreadsheet->getActiveSheet('Акт передачи оборудования');

                    $sheet->setCellValue('N6', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('J11', $test['ystaw']); #    устав
                    $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
                    $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование
                    $sheet->setCellValue('H58', $test['FIO']); # ФИО сокращенное ???????????



                    $sheet = $spreadsheet->getActiveSheet('Акт передачи роутера');

                    $sheet->setCellValue('N6', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('J11', $test['ystaw']); #    устав
                    $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
                    $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование





                    $sheet = $spreadsheet->getActiveSheet('Акт оказания услуг Админ');

                    $sheet->setCellValue('N6', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('J11', $test['ystaw']); #    устав
                    $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
                    $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование



                    $sheet = $spreadsheet->getActiveSheet('Акт оказания услуг СШ');

                    $sheet->setCellValue('N6', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('J11', $test['ystaw']); #    устав
                    $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
                    $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование



                    $sheet = $spreadsheet->getActiveSheet('Акт оказания услуг ГШ');

                    $sheet->setCellValue('N6', $data['spot']); # Должность в родительном
                    $sheet->setCellValue('A8', $test['FIO']); # ФИО в род
                    $sheet->setCellValue('J11', $test['ystaw']); #    устав
                    $sheet->setCellValue('J28', $test['fullNameC30']); #  A30, A31 полное наименование c30
                    $sheet->setCellValue('I33', $test['adres']);   #A34   адрес
                    $sheet->setCellValue('A56', $data['spot']); # должность в именит пад ?????????????
                    $sheet->setCellValue('A57', $test['shortName']);  # сокращенное наименование




            */

            $writer = new Xlsx($spreadsheet);
            $writer->save($this->getParameter('kernel.project_dir') . '/src/TemplatesXlsx/hello.xlsx');

#$spreadsheet=$file_tmpl
            /*
            $file1 = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet('Акт готовности');

            #$file1->setActiveSheetIndex(1);
            #$sheet = $file1->getActiveSheet();
            #$file_tmpl->addSheet($sheet,1);

            $file1 ->addExternalSheet($sheet);
            $writerr = new Xlsx($file1);
            $writerr ->save('C:\OpenServer\domains\webexcel\public\sampleData\hellow.xlsx');
            */


        }


        /*


            $repository = $this->getDoctrine()->getRepository(Database::class);


            $products = $repository->find(3);

            print_r($products->getFIO());

            #    return $this->render('admin/user/change.html.twig',['FIO', 'ystaw', 'fullNameC30' ,'adres', 'spot','shortName',  'spotNameFP', 'mainFIOFP']);
            return $this->render('admin/user/change.html.twig',[
            'user_first_name' =>0

        ]);
        */
        return $this->render('admin/user/change.html.twig', array(
            'form' => $form_record_test->createView()));
    }


    /**
     * @Route("/admin/user/add/record", name="admin_user_record")
     */
    public function index(Request $request)
    {
        #$forRender = parent::renderDefault();
        #return $this->render('admin/index.html.twig', $forRender);
        /*
        Должность в родительном падеже
        ФИО  в родительном падеже
        Устав, приказ (на основании чего)
        Полное наименование СЗО
        Адрес
        Должность
        Сокращенное наименование
        ФИО сокращенное
        Должность и ФИО
        Должность и наименование ФП
        ФИО заведующей ФП
        */
        #$record = new Record();
        $form_record = $this->createFormBuilder()
            ->add('FIO', TextareaType::class, array('label' => 'ФИО  в родительном падеже'))
            ->add('ystaw', TextareaType::class, array('label' => 'Устав, приказ (на основании чего)'))
            ->add('fullNameC30', TextareaType::class, array('label' => 'Полное наименование СЗО'))
            ->add('adres', TextareaType::class, array('label' => 'Адрес'))
            ->add('spot', TextareaType::class, array('label' => 'Должность'))
            ->add('shortName', TextareaType::class, array('label' => 'Сокращенное наименование СЗО'))
            ->add('spotNameFP', TextareaType::class, array('label' => '#  Должность и наименование ФП'))
            ->add('mainFIOFP', TextareaType::class, array('label' => 'ФИО заведующей ФП'))
            ->add('save', SubmitType::class, array('label' => 'record'))
            ->getForm();
        $form_record->handleRequest($request);

        if ($form_record->isSubmitted() && $form_record->isValid()) {


            $session = new Session();
            //$session->start();
            $data = $form_record->getData();
            $session->set('FIO', $data);

            return $this->redirectToRoute('admin_user_refresh');
            #return $this->render('user/change.html.twig',[
#                      'form' => $form_record->createView(),
#                ]);

        }

        return $this->render('user/create.html.twig', array(
            'form' => $form_record->createView(),
        ));
    }
}
