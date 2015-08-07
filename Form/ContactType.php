<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-18
 */
    
namespace EdsiTech\GandiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use EdsiTech\GandiBundle\Model\Contact;

class ContactType extends AbstractType
{
            
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('type', 'choice', array(
                'label' => 'contact.label.type',
                'choices' => Contact::getTypes()
            ))
            ->add('firstname', 'text', array(
                'label' => 'contact.label.firstname',
                'required'  => true,
            ))
            ->add('lastname', 'text', array(
                'label' => 'contact.label.lastname',
                'required'  => true,
            ))
            ->add('street', 'textarea', array(
                'label' => 'contact.label.street',
                'required'  => true,
            ))
            ->add('zip', 'text', array(
                'label' => 'contact.label.zip',
                'required'  => true,
            ))
            ->add('city', 'text', array(
                'label' => 'contact.label.city',
                'required'  => true,
            ))
            ->add('country', 'country', array(
                'label' => 'contact.label.country',
                'required'  => true,
            ))
            ->add('email', 'email', array(
                'label' => 'contact.label.email',
                'required'  => true,
            ))
            ->add('phone', 'text', array(
                'label' => 'contact.label.phone',
                'required'  => true,
            ))
            ->add('mobile', 'text', array(
                'label' => 'contact.label.mobile',
                'required'  => false,
            ))
            ->add('fax', 'text', array(
                'label' => 'contact.label.fax',
                'required'  => false,
            ))
            ->add('language', 'language', array(
                'label' => 'contact.label.language',
                'required'  => false,
            ))
/*
            ->add('hide_address', 'checkbox', array(
                'label' => 'contact.label.hide_address',
                'required'  => false,
            ))
            ->add('hide_email', 'checkbox', array(
                'label' => 'contact.label.hide_email',
                'required'  => false,
            ))
*/
        ;
        
        //extra parameters
        $builder->add('extra_parameters', 'collection', array(
            'type'   => 'text',
            'label' => 'contact.label.extra_parameters',
            'options'  => array(
                'required'  => false,
                'allow_add' => true,
                'allow_delete' => true,
                'options' => array(
                    'choices' => Contact::getExtraParametersTypes()
                )
            ),
        ));
        
        //password if new
        if(null === $builder->getData()->getHandle()) {
            $builder->add('password','password', array(
                'required'  => true,
                'label' => 'contact.label.password',
            ));
        }
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();

                $data = $event->getData();

                if(Contact::TYPE_PERSON !== $data->getType()) {
                    $form->add('company', 'text', array(
                        'required'  => true,
                        'label' => 'contact.label.company',
                    ));
                    $form->add('vat_number', 'text', array(
                        'required'  => false,
                        'label' => 'contact.label.vat_number',
                    ));
                }

            }
        );
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edsitech_gandibundle_contact';
    }
}
