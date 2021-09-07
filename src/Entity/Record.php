<?php
namespace App\Entity;

class Record
{
  #  protected $positionInCompany; #  Должность в родительном падеже
    protected $FIO;               #  ФИО  в родительном падеже
    protected $ystaw;             #  Устав, приказ (на основании чего)
    protected $fullNameC30;       #  Полное наименование СЗО
    protected $adres;             #  Адрес
    protected $spot;              #  Должность
    protected $shortName;         #  Сокращенное наименование
  #  protected $shortFIO;          #  ФИО сокращенное
                                  #  Должность и ФИО
    protected $spotNameFP;        #  Должность и наименование ФП
    protected $mainFIOFP;        #  ФИО заведующей ФП


  /*  public function getPositionInCompany() #  Должность в родительном падеже
    {
      return $this->positionInCompany;
    }

    public function setPositionInCompany($positionInCompany)
    {
      $this->positionInCompany = $positionInCompany;
    }
  */
    public function getFIO() # ФИО  в родительном падеже
    {
        return $this->FIO;
    }

    public function setFIO($FIO)
    {
        $this->FIO = $FIO;
    }

    public function getYstaw() #  Устав, приказ (на основании чего)
    {
        return $this->ystaw;
    }

    public function setYstaw($ystaw)
    {
        $this->ystaw = $ystaw;
    }

    public function getFullNameC30() #  Устав, приказ (на основании чего)
    {
        return $this->fullNameC30;
    }

    public function setFullNameC30($fullNameC30)
    {
        $this->fullNameC30 = $fullNameC30;
    }

    public function getAdres() #  Адрес
    {
        return $this->adres;
    }

    public function setAdres($adres)
    {
        $this->adres = $adres;
    }


    public function getSpot()  #  Должность
    {
        return $this->spot;
    }

    public function setSpot($spot)
    {
        $this->spot = $spot;
    }


    public function getShortName() # Сокращенное наименование
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }


/*    public function getShortFIO()  #  ФИО сокращенное
    {
        return $this->shortFIO;
    }

    public function setShortFIO($shortFIO)
    {
        $this->shortFIO = $shortFIO;
    }
*/

    public function getSpotNameFP() #  Должность и наименование ФП
    {
        return $this->spotNameFP;
    }

    public function setSpotNameFP($spotNameFP)
    {
        $this->spotNameFP = $spotNameFP;
    }


    public function getMainFIOFP() #  ФИО заведующей ФП
    {
        return $this->mainFIOFP;
    }

    public function setMainFIOFP($mainFIOFP)
    {
        $this->mainFIOFP = $mainFIOFP;
    }

}
