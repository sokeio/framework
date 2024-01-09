<?php

namespace Sokeio\Admin\Components\Common\Concerns;

trait WithButtonColor
{

    public function ButtonColor($ButtonColor)
    {
        return $this->setKeyValue('ButtonColor', $ButtonColor);
    }
    public function getButtonColor()
    {
        return $this->getValue('ButtonColor');
    }
    public function Primary($prev = '')
    {
        return $this->ButtonColor($prev . '-primary');
    }
    public function Secondary($prev = '')
    {
        return $this->ButtonColor($prev . '-secondary');
    }
    public function Success($prev = '')
    {
        return $this->ButtonColor($prev . '-success');
    }
    public function Warning($prev = '')
    {
        return $this->ButtonColor($prev . '-warning');
    }
    public function Danger($prev = '')
    {
        return $this->ButtonColor($prev . '-danger');
    }
    public function Info($prev = '')
    {
        return $this->ButtonColor($prev . '-info');
    }
    public function Dark($prev = '')
    {
        return $this->ButtonColor($prev . '-dark');
    }
    public function Light($prev = '')
    {
        return $this->ButtonColor($prev . '-light');
    }
    public function Blue($prev = '')
    {
        return $this->ButtonColor($prev . '-blue');
    }
    public function Azure($prev = '')
    {
        return $this->ButtonColor($prev . '-azure');
    }
    public function Indigo($prev = '')
    {
        return $this->ButtonColor($prev . '-indigo');
    }
    public function Purple($prev = '')
    {
        return $this->ButtonColor($prev . '-purple');
    }
    public function Pink($prev = '')
    {
        return $this->ButtonColor($prev . '-pink');
    }
    public function Red($prev = '')
    {
        return $this->ButtonColor($prev . '-red');
    }
    public function Orange($prev = '')
    {
        return $this->ButtonColor($prev . '-orange');
    }
    public function Yellow($prev = '')
    {
        return $this->ButtonColor($prev . '-yellow');
    }
    public function Lime($prev = '')
    {
        return $this->ButtonColor($prev . '-lime');
    }
    public function Green($prev = '')
    {
        return $this->ButtonColor($prev . '-green');
    }
    public function Teal($prev = '')
    {
        return $this->ButtonColor($prev . '-teal');
    }
    public function Cyan($prev = '')
    {
        return $this->ButtonColor($prev . '-cyan');
    }

    //Outline
    public function OutlinePrimary()
    {
        return $this->Primary('-outline');
    }
    public function OutlineSecondary()
    {
        return $this->Secondary('-outline');
    }
    public function OutlineSuccess()
    {
        return $this->Success('-outline');
    }
    public function OutlineWarning()
    {
        return $this->Warning('-outline');
    }
    public function OutlineDanger()
    {
        return $this->Danger('-outline');
    }
    public function OutlineInfo()
    {
        return $this->Info('-outline');
    }
    public function OutlineDark()
    {
        return $this->Dark('-outline');
    }
    public function OutlineLight()
    {
        return $this->Light('-outline');
    }
    public function OutlineBlue()
    {
        return $this->Blue('-outline');
    }
    public function OutlineAzure()
    {
        return $this->Azure('-outline');
    }
    public function OutlineIndigo()
    {
        return $this->Indigo('-outline');
    }
    public function OutlinePurple()
    {
        return $this->Purple('-outline');
    }
    public function OutlinePink()
    {
        return $this->Pink('-outline');
    }
    public function OutlineRed()
    {
        return $this->Red('-outline');
    }
    public function OutlineOrange()
    {
        return $this->Orange('-outline');
    }
    public function OutlineYellow()
    {
        return $this->Yellow('-outline');
    }
    public function OutlineLime()
    {
        return $this->Lime('-outline');
    }
    public function OutlineGreen()
    {
        return $this->Green('-outline');
    }
    public function OutlineTeal()
    {
        return $this->Teal('-outline');
    }
    public function OutlineCyan()
    {
        return $this->Cyan('-outline');
    }
    //Ghost
    public function GhostPrimary()
    {
        return $this->Primary('-ghost');
    }
    public function GhostSecondary()
    {
        return $this->Secondary('-ghost');
    }
    public function GhostSuccess()
    {
        return $this->Success('-ghost');
    }
    public function GhostWarning()
    {
        return $this->Warning('-ghost');
    }
    public function GhostDanger()
    {
        return $this->Danger('-ghost');
    }
    public function GhostInfo()
    {
        return $this->Info('-ghost');
    }
    public function GhostDark()
    {
        return $this->Dark('-ghost');
    }
    public function GhostLight()
    {
        return $this->Light('-ghost');
    }
    public function GhostBlue()
    {
        return $this->Blue('-ghost');
    }
    public function GhostAzure()
    {
        return $this->Azure('-ghost');
    }
    public function GhostIndigo()
    {
        return $this->Indigo('-ghost');
    }
    public function GhostPurple()
    {
        return $this->Purple('-ghost');
    }
    public function GhostPink()
    {
        return $this->Pink('-ghost');
    }
    public function GhostRed()
    {
        return $this->Red('-ghost');
    }
    public function GhostOrange()
    {
        return $this->Orange('-ghost');
    }
    public function GhostYellow()
    {
        return $this->Yellow('-ghost');
    }
    public function GhostLime()
    {
        return $this->Lime('-ghost');
    }
    public function GhostGreen()
    {
        return $this->Green('-ghost');
    }
    public function GhostTeal()
    {
        return $this->Teal('-ghost');
    }
    public function GhostCyan()
    {
        return $this->Cyan('-ghost');
    }
}
