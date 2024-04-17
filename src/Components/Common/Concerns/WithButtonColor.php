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
    public function primary($prev = ''): static
    {
        return $this->buttonColor($prev . '-primary');
    }
    public function secondary($prev = ''): static
    {
        return $this->buttonColor($prev . '-secondary');
    }
    public function success($prev = ''): static
    {
        return $this->buttonColor($prev . '-success');
    }
    public function warning($prev = ''): static
    {
        return $this->buttonColor($prev . '-warning');
    }
    public function danger($prev = ''): static
    {
        return $this->buttonColor($prev . '-danger');
    }
    public function info($prev = ''): static
    {
        return $this->buttonColor($prev . '-info');
    }
    public function dark($prev = ''): static
    {
        return $this->buttonColor($prev . '-dark');
    }
    public function light($prev = ''): static
    {
        return $this->buttonColor($prev . '-light');
    }
    public function blue($prev = ''): static
    {
        return $this->buttonColor($prev . '-blue');
    }
    public function azure($prev = ''): static
    {
        return $this->buttonColor($prev . '-azure');
    }
    public function indigo($prev = ''): static
    {
        return $this->buttonColor($prev . '-indigo');
    }
    public function purple($prev = ''): static
    {
        return $this->buttonColor($prev . '-purple');
    }
    public function pink($prev = ''): static
    {
        return $this->buttonColor($prev . '-pink');
    }
    public function red($prev = ''): static
    {
        return $this->buttonColor($prev . '-red');
    }
    public function orange($prev = ''): static
    {
        return $this->buttonColor($prev . '-orange');
    }
    public function yellow($prev = ''): static
    {
        return $this->buttonColor($prev . '-yellow');
    }
    public function lime($prev = ''): static
    {
        return $this->buttonColor($prev . '-lime');
    }
    public function green($prev = ''): static
    {
        return $this->buttonColor($prev . '-green');
    }
    public function teal($prev = ''): static
    {
        return $this->buttonColor($prev . '-teal');
    }
    public function cyan($prev = ''): static
    {
        return $this->buttonColor($prev . '-cyan');
    }

    //Outline
    private const OUTLINE = '-outline';
    public function outlinePrimary(): static
    {
        return $this->primary(static::OUTLINE);
    }
    public function outlineSecondary(): static
    {
        return $this->secondary(static::OUTLINE);
    }
    public function outlineSuccess(): static
    {
        return $this->success(static::OUTLINE);
    }
    public function outlineWarning(): static
    {
        return $this->warning(static::OUTLINE);
    }
    public function outlineDanger(): static
    {
        return $this->danger(static::OUTLINE);
    }
    public function outlineInfo(): static
    {
        return $this->info(static::OUTLINE);
    }
    public function outlineDark(): static
    {
        return $this->dark(static::OUTLINE);
    }
    public function outlineLight(): static
    {
        return $this->light(static::OUTLINE);
    }
    public function outlineBlue(): static
    {
        return $this->blue(static::OUTLINE);
    }
    public function outlineAzure(): static
    {
        return $this->azure(static::OUTLINE);
    }
    public function outlineIndigo(): static
    {
        return $this->indigo(static::OUTLINE);
    }
    public function outlinePurple(): static
    {
        return $this->purple(static::OUTLINE);
    }
    public function outlinePink(): static
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
    public function outlineLime(): static
    {
        return $this->lime(static::OUTLINE);
    }
    public function outlineGreen(): static
    {
        return $this->green(static::OUTLINE);
    }
    public function outlineTeal(): static
    {
        return $this->teal(static::OUTLINE);
    }
    public function outlineCyan(): static
    {
        return $this->cyan(static::OUTLINE);
    }
    //Ghost
    private const GHOST = '-ghost';
    public function ghostPrimary(): static
    {
        return $this->primary(static::GHOST);
    }
    public function ghostSecondary(): static
    {
        return $this->secondary(static::GHOST);
    }
    public function ghostSuccess(): static
    {
        return $this->success(static::GHOST);
    }
    public function ghostWarning(): static
    {
        return $this->warning(static::GHOST);
    }
    public function ghostDanger(): static
    {
        return $this->danger(static::GHOST);
    }
    public function ghostInfo(): static
    {
        return $this->info(static::GHOST);
    }
    public function ghostDark(): static
    {
        return $this->dark(static::GHOST);
    }
    public function ghostLight(): static
    {
        return $this->light(static::GHOST);
    }
    public function ghostBlue(): static
    {
        return $this->blue(static::GHOST);
    }
    public function ghostAzure(): static
    {
        return $this->azure(static::GHOST);
    }
    public function ghostIndigo(): static
    {
        return $this->indigo(static::GHOST);
    }
    public function ghostPurple(): static
    {
        return $this->purple(static::GHOST);
    }
    public function ghostPink(): static
    {
        return $this->pink(static::GHOST);
    }
    public function ghostRed(): static
    {
        return $this->red(static::GHOST);
    }
    public function ghostOrange(): static
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
    public function ghostTeal(): static
    {
        return $this->teal(static::GHOST);
    }
    public function ghostCyan(): static
    {
        return $this->cyan(static::GHOST);
    }
}
