<?php

namespace AppVentus\ListBundle\Component;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * AvListFactory.
 *
 * service id : av_list
 */
class AvListFactory
{
    /** @var RequestStack */
    protected $request;
    /** @var EngineInterface */
    protected $templating;
    /** @var TranslatorInterface */
    protected $translator;

    /**
     * @param Request             $request    The request.
     * @param EngineInterface     $templating The templating engine.
     * @param TranslatorInterface $translator The translator service
     */
    public function __construct(RequestStack $request, EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->request = $request->getCurrentRequest();
    }

    /**
     * Return AvList.
     *
     * @param mixed  $data     The data.
     * @param string $template Template to render.
     * @param array  $options  Array of options.
     *
     * @return AvList
     */
    public function getList($data, $sort, $order, $template, array $options = [])
    {
        //get the translations if they are not given
        if (!isset($options['prev_message'])) {
            $options['prev_message'] = $this->translator->trans('prev_message', [], 'av_list');
        }
        if (!isset($options['next_message'])) {
            $options['next_message'] = $this->translator->trans('next_message', [], 'av_list');
        }

        switch (true) {
            case $data instanceof \Doctrine\ORM\QueryBuilder:
                $list = new AvListQueryBuilder($this->request, $this->templating, $data, $sort, $order, $template, $options);
                break;
            case is_array($data):
                $list = new AvListArray($this->request, $this->templating, $data, $sort, $order, $template, $options);
                break;

            default:
                break;
        }

        return $list;
    }
}

