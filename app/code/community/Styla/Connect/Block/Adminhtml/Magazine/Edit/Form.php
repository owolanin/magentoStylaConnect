<?php

/**
 * Styla_Connect_Block_Adminhtml_Magazine_Edit_Form
 *
 */
class Styla_Connect_Block_Adminhtml_Magazine_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init form
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('magazine_form');
        $this->setTitle($this->__('Magazine Information'));
    }

    protected function _prepareForm()
    {
        /** @var Styla_Connect_Model_Magazine $magazine */
        $magazine = Mage::registry('current_magazine');

        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getData('action'),
            'method' => 'post'
        ));

        $fieldset = $form->addFieldset('base', array(
            'legend' => $this->__('Magazine Data'),
            'class'  => 'fieldset-wide'
        ));

        if ($magazine->isDefault()) {
            $fieldset->setData('comment', $this->__('This is your default magazine! If you want to disable a magazine for a certain store, create a new magazine with the same front name and disable it.'));
        } else {
            if (!$magazine->getId()) {
                $fieldset->setData('comment', $this->__('You are going to create a new magazine which also need to exist on styla side before it can work.'));
            }
            $fieldset->addField('store_id', 'select', array(
                'name'               => 'store_id',
                'label'              => $this->__('Store'),
                'title'              => $this->__('Store'),
                'required'           => true,
                'values'             => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(true),
                'after_element_html' => '<p class="note"><span>' . $this->__('The store the magazine should be active on') . '</span></p>'
            ));
        }

        if ($magazine->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('is_active', 'select', [
            'name'     => 'is_active',
            'label'    => $this->__('Active'),
            'title'    => $this->__('Active'),
            'required' => true,
            'values'   => array(
                '1' => $this->__('Yes'),
                '0' => $this->__('No'),
            )
        ]);

        $fieldset->addField('client_name', 'text', [
            'name'               => 'client_name',
            'label'              => $this->__('Client Name'),
            'title'              => $this->__('Client Name'),
            'required'           => true,
            'after_element_html' => '<p class="note"><span>' . $this->__('Your client name, this wil be given you by styla.') . '</span></p>'
        ]);

        $fieldset->addField('front_name', 'text', [
            'name'               => 'front_name',
            'label'              => $this->__('Front Name'),
            'title'              => $this->__('Front Name'),
            'required'           => true,
            'after_element_html' => '<p class="note"><span>' . $this->__('Your magazine uri will start with this name, and will be used like this: www.domain.com/[FRONT_NAME]/rest_of_the_url') . '</span></p>'
        ]);

        $fieldset->addField('use_magento_layout', 'select', [
            'name'               => 'use_magento_layout',
            'label'              => $this->__('Use magento layout'),
            'title'              => $this->__('Use magento layout'),
            'required'           => true,
            'values'             => array(
                '1' => $this->__('Yes'),
                '0' => $this->__('No'),
            ),
            'after_element_html' => '<p class="note"><span>' . $this->__('yes - the Styla magazine page will be wrapped within a normal Magento header and footer; no - only the magazine content will be visible') . '</span></p>'
        ]);

        $includeInNavigation = $fieldset->addField('include_in_navigation', 'select', [
            'name'               => 'include_in_navigation',
            'label'              => $this->__('Include in navigation'),
            'title'              => $this->__('Include in navigation'),
            'required'           => true,
            'values'             => array(
                '1' => $this->__('Yes'),
                '0' => $this->__('No'),
            ),
            'after_element_html' => '<p class="note"><span>' . $this->__('yes - a "magazine" link will be added to the main (top) navigation menu.') . '</span></p>'
        ]);

        $navigationLabel = $fieldset->addField('navigation_label', 'text', [
            'name'               => 'navigation_label',
            'required'           => true,
            'label'              => $this->__('Navigation label'),
            'title'              => $this->__('Navigation label'),
            'after_element_html' => '<p class="note"><span>' . $this->__('The label used for the navigation menu link.') . '</span></p>'
        ]);

        $dependenceBlock = $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence');
        $dependenceBlock
            ->addFieldMap($includeInNavigation->getHtmlId(), $includeInNavigation->getName())
            ->addFieldMap($navigationLabel->getHtmlId(), $navigationLabel->getName())
            ->addFieldDependence(
                $navigationLabel->getName(),
                $includeInNavigation->getName(),
                1
            );

        $this->setChild('form_after', $dependenceBlock);
        $this->setForm($form);

        $form->setUseContainer(true);
        $form->setValues($magazine->getData());

        return parent::_prepareForm();
    }
}
