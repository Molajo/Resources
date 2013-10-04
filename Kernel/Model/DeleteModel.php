<?php
namespace Molajo\Model;

use Molajo\Model\Api\DeleteModelInterface;

/**
 * As instructed by the Delete Controller. the Delete Model uses model registry data to prepare
 * data, create and run SQL statements needed to delete data.
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class DeleteModel extends Model implements DeleteModelInterface
{
    /**
     * delete - deletes a row from a table
     *
     * @param   $data
     * @param   $model_registry
     *
     * @return bool
     * @since   1.0
     */
    public function delete($data, $model_registry)
    {
        $table_name     = $this->registry->get($model_registry, 'table_name');
        $primary_prefix = $this->registry->get($model_registry, 'primary_prefix');
        $name_key       = $this->registry->get($model_registry, 'name_key');
        $primary_key    = $this->registry->get($model_registry, 'primary_key');

        /** Build Delete Statement */
        $deleteSQL = 'DELETE FROM ' . $this->database->qn($table_name);

        if (isset($data->$primary_key)) {
            $deleteSQL .= ' WHERE ' . $this->database->qn($primary_key) . ' = ' . (int)$data->$primary_key;

        } elseif (isset($data->$name_key)) {
            $deleteSQL .= ' WHERE ' . $this->database->qn($name_key) . ' = ' . $this->database->q(
                    $data->$name_key
                );

        } else {
            //only 1 row at a time with primary title or id key
            return false;
        }

        if (isset($data->catalog_type_id)) {
            $deleteSQL .= ' AND ' . $this->database->qn(
                    'catalog_type_id'
                ) . ' = ' . (int)$data->catalog_type_id;
        }

        $sql = $deleteSQL;

        $this->database->setQuery($sql);

        $this->database->execute();

        return true;
    }
}
