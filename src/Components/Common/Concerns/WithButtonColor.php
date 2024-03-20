<?php

namespace Sokeio\Components\Common\Concerns;

trait WithButtonColor
{

    public function buttonColor($buttonColor): static
    {
        return $this->setKeyValue('buttonColor', $buttonColor);
    }
    public function getButtonColor()
    {
        return $this->getValue('buttonColor');
    }
    public function primary($prev = '')
    {
        return $this->buttonColor($prev . '-primary');
    }
    public function secondary($prev = '')
    {
        return $this->buttonColor($prev . '-secondary');
    }
    public function success($prev = '')
    {
        return $this->buttonColor($prev . '-success');
    }
    public function warning($prev = '')
    {
        return $this->buttonColor($prev . '-warning');
    }
    public function danger($prev = '')
    {
        return $this->buttonColor($prev . '-danger');
    }
    public function info($prev = '')
    {
        return $this->buttonColor($prev . '-info');
    }
    public function dark($prev = '')
    {
        return $this->buttonColor($prev . '-dark');
    }
    public function light($prev = '')
    {
        return $this->buttonColor($prev . '-light');
    }
    public function blue($prev = '')
    {
        return $this->buttonColor($prev . '-blue');
    }
    public function azure($prev = '')
    {
        return $this->buttonColor($prev . '-azure');
    }
    public function indigo($prev = '')
    {
        return $this->buttonColor($prev . '-indigo');
    }
    public function purple($prev = '')
    {
        return $this->buttonColor($prev . '-purple');
    }
    public function pink($prev = '')
    {
        return $this->buttonColor($prev . '-pink');
    }
    public function red($prev = '')
    {
        return $this->buttonColor($prev . '-red');
    }
    public function orange($prev = '')
    {
        return $this->buttonColor($prev . '-orange');
    }
    public function yellow($prev = '')
    {
        return $this->buttonColor($prev . '-yellow');
    }
    public function lime($prev = '')
    {
        return $this->buttonColor($prev . '-lime');
    }
    public function green($prev = '')
    {
        return $this->buttonColor($prev . '-green');
    }
    public function teal($prev = '')
    {
        return $this->buttonColor($prev . '-teal');
    }
    public function cyan($prev = '')
    {
        return $this->buttonColor($prev . '-cyan');
    }

    //Outline
    private const OUTLINE = '-outline';
    public function outlinePrimary()
    {
        return $this->primary(static::OUTLINE);
    }
    public function outlineSecondary()
    {
        return $this->secondary(static::OUTLINE);
    }
    public function outlineSuccess()
    {
        return $this->success(static::OUTLINE);
    }
    public function outlineWarning()
    {
        return $this->warning(static::OUTLINE);
    }
    public function outlineDanger()
    {
        return $this->danger(static::OUTLINE);
    }
    public function outlineInfo()
    {
        return $this->info(static::OUTLINE);
    }
    public function outlineDark()
    {
        return $this->dark(static::OUTLINE);
    }
    public function outlineLight()
    {
        return $this->light(static::OUTLINE);
    }
    public function outlineBlue()
    {
        return $this->blue(static::OUTLINE);
    }
    public function outlineAzure()
    {
        return $this->azure(static::OUTLINE);
    }
    public function outlineIndigo()
    {
        return $this->indigo(static::OUTLINE);
    }
    public function outlinePurple()
    {
        return $this->purple(static::OUTLINE);
    }
    public function outlinePink()
    {
        return $this->pink(static::OUTLINE);
    }
    public function outlineRed()
    {
        return $this->red(static::OUTLINE);
    }
    public function outlineOrange()
    {
        return $this->orange(static::OUTLINE);
    }
    public function outlineYellow()
    {
        return $this->yellow(static::OUTLINE);
    }
    public function outlineLime()
    {
        return $this->lime(static::OUTLINE);
    }
    public function outlineGreen()
    {
        return $this->green(static::OUTLINE);
    }
    public function outlineTeal()
    {
        return $this->teal(static::OUTLINE);
    }
    public function outlineCyan()
    {
        return $this->cyan(static::OUTLINE);
    }
    //Ghost
    private const GHOST = '-ghost';
    public function ghostPrimary()
    {
        return $this->primary(static::GHOST);
    }
    public function ghostSecondary()
    {
        return $this->secondary(static::GHOST);
    }
    public function ghostSuccess()
    {
        return $this->success(static::GHOST);
    }
    public function ghostWarning()
    {
        return $this->warning(static::GHOST);
    }
    public function ghostDanger()
    {
        return $this->danger(static::GHOST);
    }
    public function ghostInfo()
    {
        return $this->info(static::GHOST);
    }
    public function ghostDark()
    {
        return $this->dark(static::GHOST);
    }
    public function ghostLight()
    {
        return $this->light(static::GHOST);
    }
    public function ghostBlue()
    {
        return $this->blue(static::GHOST);
    }
    public function ghostAzure()
    {
        return $this->azure(static::GHOST);
    }
    public function ghostIndigo()
    {
        return $this->indigo(static::GHOST);
    }
    public function ghostPurple()
    {
        return $this->purple(static::GHOST);
    }
    public function ghostPink()
    {
        return $this->pink(static::GHOST);
    }
    public function ghostRed()
    {
        return $this->red(static::GHOST);
    }
    public function ghostOrange()
    {
        return $this->orange(static::GHOST);
    }
    public function ghostYellow()
    {
        return $this->yellow(static::GHOST);
    }
    public function ghostLime()
    {
        return $this->lime(static::GHOST);
    }
    public function ghostGreen()
    {
        return $this->green(static::GHOST);
    }
    public function ghostTeal()
    {
        return $this->teal(static::GHOST);
    }
    public function ghostCyan()
    {
        return $this->cyan(static::GHOST);
    }
}
