<?php

namespace BytePlatform;

class Item extends ItemCallback
{
    public const Col = "col";
    public const Col1 = "col1";
    public const Col2 = "col2";
    public const Col3 = "col3";
    public const Col4 = "col4";
    public const Col5 = "col5";
    public const Col6 = "col6";
    public const Col7 = "col7";
    public const Col8 = "col8";
    public const Col9 = "col9";
    public const Col10 = "col10";
    public const Col11 = "col11";
    public const Col12 = "col12";
    public static function getSize($name)
    {
        switch ($name) {
            case "col1":
                return "col-xs-12 col-sm-12 col-md-1 col-lg-1";
            case "col2":
                return "col-xs-12 col-sm-12 col-md-1 col-lg-2";
            case "col3":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-3";
            case "col4":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-4";
            case "col5":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-5";
            case "col6":
                return "col-xs-12 col-sm-12 col-md-4 col-lg-6";
            case "col7":
                return "col-xs-12 col-sm-12 col-md-8 col-lg-7";
            case "col8":
                return "col-xs-12 col-sm-12 col-md-8 col-lg-8";
            case "col9":
                return "col-xs-12 col-sm-12 col-md-8 col-lg-9";
            case "col10":
                return "col-xs-12 col-sm-12 col-md-12 col-lg-10";
            case "col11":
                return "col-xs-12 col-sm-12 col-md-12 col-lg-11";
            case "col12":
                return "col-xs-12 col-sm-12 col-md-12 col-lg-12";
            default:
                return "col";
        }
    }
    public static function getPolls()
    {
        return  [
            ['value' => '', 'text' => 'No Poll'],
            ['value' => 'poll', 'text' => 'Poll'],
            ['value' => 'poll.keep-alive', 'text' => 'keep-alive'],
            ['value' => 'poll.visible', 'text' => 'visible'],
            ['value' => 'poll.15s', 'text' => '15s'],
            ['value' => 'poll.10s', 'text' => '10s'],
            ['value' => 'poll.5s', 'text' => '5s'],
            ['value' => 'poll.15000ms', 'text' => '15000ms'],
        ];
    }
    public static function getColumnValue()
    {
        return  [
            ['value' => 'col', 'text' => 'Col'],
            ['value' => 'col1', 'text' => 'Col1'],
            ['value' => 'col2', 'text' => 'Col2'],
            ['value' => 'col3', 'text' => 'Col3'],
            ['value' => 'col4', 'text' => 'Col4'],
            ['value' => 'col5', 'text' => 'Col5'],
            ['value' => 'col6', 'text' => 'Col6'],
            ['value' => 'col7', 'text' => 'Col7'],
            ['value' => 'col8', 'text' => 'Col8'],
            ['value' => 'col9', 'text' => 'Col9'],
            ['value' => 'col10', 'text' => 'Col10'],
            ['value' => 'col11', 'text' => 'Col11'],
            ['value' => 'col12', 'text' => 'Col12'],
        ];
    }
    public static function getColors()
    {
        return [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'light',
            'dark',

            'blue',
            'azure',
            'indigo',
            'purple',
            'pink',
            'red',
            'orange',
            'yellow',
            'lime',
            'green',
            'teal',
            'cyan',

            'blue-lt',
            'azure-lt',
            'indigo-lt',
            'purple-lt',
            'pink-lt',
            'red-lt',
            'orange-lt',
            'yellow-lt',
            'lime-lt',
            'green-lt',
            'teal-lt',
            'cyan-lt',

            'gray-50',
            'gray-100',
            'gray-200',
            'gray-300',
            'gray-400',
            'gray-500',
            'gray-600',
            'gray-700',
            'gray-800',
            'gray-900',

            'facebook',
            'twitter',
            'linkedin',
            'google',
            'youtube',
            'vimeo',
            'dribbble',
            'github',
            'instagram',
            'pinterest',
            'vk',
            'rss',
            'flickr',
            'bitbucket',
            'tabler'
        ];
    }
    private function __construct($field)
    {
        $this->Field($field)
            ->Column(self::Col6)
            ->ValueDefault('')
            ->Data(function (Item $item, BaseManager $manager) {
                return $manager->getData();
            })
            ->DisableEdit(function (Item $item, BaseManager $manager) {
                return !$manager->IsTable() || $manager->EditInTable();
            })
            ->When(function () {
                return true;
            })
            ->DisableFilter(function () {
                return true;
            })
            ->DisableSort(function () {
                return true;
            })->DataText(function (Item $item) {
                return  $item->getData()->{$item->getField()};
            })
            ->DataId(function (Item $item, BaseManager $manager) {
                return  $item->getData()->id;
            })->ModelForm(function (Item $item, BaseManager $manager) {
                if ($manager->IsTable()) {
                    $prod =  ItemForms::FormId($item->getDataId());
                    return "formTable." .  $prod . ".";
                } else {
                    return "form.";
                }
            })->NoBindData(function () {
                return false;
            });
    }
    public static function Add($field)
    {
        return new self($field);
    }

    public function DataId($dataId)
    {
        return $this->setKeyValue('dataId', $dataId);
    }

    public function getDataId()
    {
        return $this->getValue('dataId');
    }

    public function Column($column)
    {
        return $this->setKeyValue('column', $column);
    }

    public function getColumn()
    {
        return $this->getValue('column');
    }

    public function getColumnSize()
    {
        return self::getSize($this->getColumn());
    }

    public function Key($key)
    {
        return $this->setKeyValue('key', $key);
    }

    public function getKey()
    {
        return $this->getValue('key');
    }
    public function Placeholder($placeholder)
    {
        return $this->setKeyValue('placeholder', $placeholder);
    }

    public function getPlaceholder()
    {
        return $this->getValue('placeholder');
    }

    public function DisableFilter($filter = null)
    {
        if ($filter == null) {
            $filter = function () {
                return false;
            };
        }
        return $this->setKeyValue('filter', $filter);
    }

    public function getFilter()
    {
        return $this->getValue('filter');
    }
    public function InputHidden($InputHidden = null)
    {
        if ($InputHidden == null) {
            $InputHidden = function () {
                return true;
            };
        }
        return $this->setKeyValue('InputHidden', $InputHidden);
    }

    public function getInputHidden()
    {
        return $this->getValue('InputHidden');
    }
    public function DisableSort($sort = null)
    {
        if ($sort == null) {
            $sort = function () {
                return false;
            };
        }
        return $this->setKeyValue('sort', $sort);
    }

    public function getSort()
    {
        return $this->getValue('sort');
    }
    public function DisableEdit($edit = null)
    {
        if ($edit == null) {
            $edit = function () {
                return false;
            };
        }
        return $this->setKeyValue('edit', $edit);
    }

    public function getEdit()
    {
        return $this->getValue('edit');
    }

    public function Field($field)
    {
        return $this->setKeyValue('field', $field);
    }
    public function getField()
    {
        return $this->getValue('field');
    }

    public function ValueDefault($ValueDefault)
    {
        return $this->setKeyValue('ValueDefault', $ValueDefault);
    }
    public function getValueDefault()
    {
        return $this->getValue('ValueDefault');
    }
    public function FieldValue($FieldValue)
    {
        return $this->setKeyValue('FieldValue', $FieldValue);
    }
    public function getFieldValue()
    {
        return $this->getValue('FieldValue');
    }
    public function FieldOption($FieldOption)
    {
        return $this->setKeyValue('FieldOption', $FieldOption);
    }
    public function getFieldOption()
    {
        return $this->getValue('FieldOption', []);
    }
    public function ModelForm($ModelForm)
    {
        return $this->setKeyValue('ModelForm', $ModelForm);
    }
    public function getModelForm()
    {
        return $this->getValue('ModelForm');
    }

    public function getModelField($field = null)
    {
        if ($field && $field != '') {
            return $this->getModelForm() . $field;
        }
        return $this->getModelForm() . $this->getField();
    }
    public function DataText($dataText)
    {
        return $this->setKeyValue('dataText', $dataText);
    }
    public function getDataText()
    {
        return $this->getValue('dataText');
    }
    public function DataOptionNone($dataOptionNone = null)
    {
        if ($dataOptionNone == null) {
            $dataOptionNone = function () {
                return [
                    'value' => '',
                    'text' => 'none'
                ];
            };
        }
        return $this->setKeyValue('dataOptionNone', $dataOptionNone);
    }
    public function getDataOptionNone()
    {
        return $this->getValue('dataOptionNone');
    }
    public function DataOption($dataOption)
    {
        return $this->setKeyValue('dataOption', $dataOption);
    }
    public function getDataOption()
    {
        return $this->getValue('dataOption');
    }
    public function Format($format)
    {
        return $this->setKeyValue('format', $format);
    }
    public function getFormat()
    {
        return $this->getValue('format');
    }

    public function Type($type)
    {
        return $this->setKeyValue('type', $type);
    }
    public function getType()
    {
        return $this->getValue('type');
    }

    public function Rules($rules)
    {
        return $this->setKeyValue('rules', $rules);
    }
    public function getRules()
    {
        return $this->getValue('rules');
    }
    public function Required($required = null)
    {
        if ($required == null) {
            $required = function () {
                return true;
            };
        }
        return $this->setKeyValue('required', $required);
    }
    public function getRequired()
    {
        return $this->getValue('required');
    }

    public function FilterView($filterView)
    {
        return $this->setKeyValue('filterView', $filterView);
    }
    public function getFilterView()
    {
        return $this->getValue('filterView');
    }
    public function ConvertToButton(): Button
    {
        return Button::Create($this->getTitle())->Manager($this->getManager())->Data($this->getData());
    }
    public function getClassContent()
    {
        return column_size($this->getColumn()) . " " . parent::getClassContent();
    }
    public function DataOptionStatus()
    {
        return $this->DataOption(function () {
            return [
                [
                    'value' => 0,
                    'text' => 'Block'
                ],
                [
                    'value' => 1,
                    'text' => 'Active'
                ]
            ];
        })->Type('select');
    }
}
