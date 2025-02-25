<?php
/***
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Cms\Test\Unit\Ui\Component\Listing\Column;

use Magento\Cms\Ui\Component\Listing\Column\BlockActions;
use Magento\Framework\Escaper;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponent\Processor;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * BlockActionsTest contains unit tests for \Magento\Cms\Ui\Component\Listing\Column\BlockActions class
 */
class BlockActionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BlockActions
     */
    private $blockActions;

    /**
     * @var Escaper|MockObject
     */
    private $escaper;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilder;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $context = $this->getMock(ContextInterface::class);

        $processor = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $context->expects(static::once())
            ->method('getProcessor')
            ->willReturn($processor);

        $this->urlBuilder = $this->getMock(UrlInterface::class);

        $this->escaper = $this->getMockBuilder(Escaper::class)
            ->disableOriginalConstructor()
            ->setMethods(['escapeHtml'])
            ->getMock();

        $this->blockActions = $objectManager->getObject(BlockActions::class, [
            'context' => $context,
            'urlBuilder' => $this->urlBuilder
        ]);

        $objectManager->setBackwardCompatibleProperty($this->blockActions, 'escaper', $this->escaper);
    }

    /**
     * @covers \Magento\Cms\Ui\Component\Listing\Column\BlockActions::prepareDataSource
     */
    public function testPrepareDataSource()
    {
        $blockId = 1;
        $title = 'block title';
        $items = [
            'data' => [
                'items' => [
                    [
                        'block_id' => $blockId,
                        'title' => $title
                    ],
                ],
            ],
        ];
        $name = 'item_name';
        $expectedItems = [
            [
                'block_id' => $blockId,
                'title' => $title,
                $name => [
                    'edit' => [
                        'href' => 'test/url/edit',
                        'label' => __('Edit'),
                    ],
                    'delete' => [
                        'href' => 'test/url/delete',
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you wan\'t to delete a %1 record?', $title)
                        ],
                        'post' => true,
                    ],
                ],
            ],
        ];

        $this->escaper->expects(static::once())
            ->method('escapeHtml')
            ->with($title)
            ->willReturn($title);

        $this->urlBuilder->expects(static::exactly(2))
            ->method('getUrl')
            ->willReturnMap(
                [
                    [
                        BlockActions::URL_PATH_EDIT,
                        [
                            'block_id' => $blockId,
                        ],
                        'test/url/edit',
                    ],
                    [
                        BlockActions::URL_PATH_DELETE,
                        [
                            'block_id' => $blockId,
                        ],
                        'test/url/delete',
                    ],
                ]
            );

        $this->blockActions->setData('name', $name);

        $actual = $this->blockActions->prepareDataSource($items);
        static::assertEquals($expectedItems, $actual['data']['items']);
    }
}
