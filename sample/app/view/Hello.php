<?php
class Sample_View_Hello extends Ethna_ViewClass
{
    public function preforward()
    {
        $this->af->setApp('now', strftime('%Y/%m/%d'));
    }
}
