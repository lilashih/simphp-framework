<?php

namespace Lilashih\Simphp\Test;

use Lilashih\Simphp\Constants\ApiMessage;
use Lilashih\Simphp\Controller\BaseApiController;

abstract class ApiTest extends BaseTest implements ITestApi
{
    /**
     * Test list API
     *
     * @return array
     */
    protected function indexTest(): array
    {
        $body = $this->mockApi('get', "/{$this->urlKey()}");
        extract($body);

        $this->assertIsArray($data[$this->collectionKey()]);
        return $data[$this->collectionKey()];
    }

    /**
     * Test detail API
     *
     * @param array $items
     *
     * @return void
     */
    protected function showTest(array $items)
    {
        foreach ($items as $item) {
            $body = $this->mockApi('get', "/{$this->urlKey()}/{$item[$this->repo()->getModel()->getKeyName()]}");
            extract($body);

            $this->assertEqualsCanonicalizing($data[$this->resourceKey()], $item);
        }
    }

    /**
     * Test create API
     *
     * @return array
     */
    protected function storeTest(): array
    {
        $item = $this->getStoreData();
        $body = $this->mockApi('post', "/{$this->urlKey()}", $item);
        extract($body);

        return $item;
    }

    /**
     * Test tree api
     *
     * @param array $item
     *
     * @return void
     */
    protected function showHasChildrenTest(array $item, $key)
    {
        $body = $this->mockApi('get', "/{$this->urlKey()}/{$item['parent_id']}");
        extract($body);

        $this->assertArrayHasKey('children', $data[$this->resourceKey()]);
        $this->assertNotNull(array_column_search($data[$this->resourceKey()]['children'], $key, $item[$key]));
    }

    /**
     * Test list API for searching 
     *
     * @param array $search
     *
     * @return array
     */
    protected function callIndexByQuery(array $search): array
    {
        $body = $this->mockApi('get', "/{$this->urlKey()}", $search);
        extract($body);

        return $data[$this->collectionKey()];
    }

    /**
     * Test list API for searching soft deleted data
     *
     * @param array $search
     *
     * @return void
     */
    protected function indexByModeTest()
    {
        $old = $this->delete();
        $items = $this->callIndexByQuery([
            'mode' => 'trashed',
        ]);

        foreach ($items as $item) {
            $this->assertNotNull($item['deleted_at']);
        }
    }

    /**
     * Test API for avoiding adding dulicate data
     *
     * @param string $key
     *
     * @return void
     */
    protected function storeDuplicateTest($key)
    {
        $data = $this->repo()->getModel()->latest()->first()->toArray();
        $body = $this->mockApi('post', "/{$this->urlKey()}", $data, 422);
        extract($body);
    }

    /**
     * Test update API
     *
     * @return void
     */
    protected function updateTest()
    {
        $old = $this->repo()->getModel()->latest()->first()->toArray();
        $new = array_merge($old, $this->getUpdateData());
        $body = $this->mockApi('put', "/{$this->urlKey()}/{$old[$this->repo()->getModel()->getKeyName()]}", $new);
        extract($body);
    }

    /**
     * Test delete API
     *
     * @return void
     */
    protected function destroyTest()
    {
        $old = $this->repo()->getModel()->latest()->first()->toArray();
        $body = $this->mockApi('delete', "/{$this->urlKey()}/{$old[$this->repo()->getModel()->getKeyName()]}");
        extract($body);
    }

    protected function delete(): array
    {
        $old = [];
        $items = $this->repo()->all(['mode' => 'trashed']);
        if (count($items) < 1) {
            $this->repo()->updateOrCreate($this->getUpdateData());
            $old = $this->repo()->getModel()->latest()->first()->toArray();
            $this->repo()->delete(-1, $old[$this->repo()->getModel()->getKeyName()]);
        } else {
            $old = end($items);
        }

        return $old;
    }
}
