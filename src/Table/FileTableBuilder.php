<?php namespace Anomaly\FileFieldType\Table;

use Anomaly\FilesModule\File\FileModel;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FileTableBuilder
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\FileFieldType\Table
 */
class FileTableBuilder extends TableBuilder
{

    /**
     * Allowed folders.
     *
     * @var array
     */
    protected $folders = [];

    /**
     * The ajax flag.
     *
     * @var bool
     */
    protected $ajax = true;

    /**
     * The table model.
     *
     * @var string
     */
    protected $model = FileModel::class;

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [
        'entry.preview' => [
            'heading' => 'anomaly.module.files::field.preview.name'
        ],
        'name'          => [
            'sort_column' => 'name',
            'wrapper'     => '
                    <strong>{value.file}</strong>
                    <br>
                    <small class="text-muted">{value.disk}://{value.folder}/{value.file}</small>
                    <br>
                    <span>{value.size} {value.keywords}</span>',
            'value'       => [
                'file'     => 'entry.name',
                'folder'   => 'entry.folder.slug',
                'keywords' => 'entry.keywords.labels',
                'disk'     => 'entry.folder.disk.slug',
                'size'     => 'entry.size_label'
            ]
        ],
        'size'          => [
            'sort_column' => 'size',
            'value'       => 'entry.readable_size'
        ],
        'mime_type',
        'folder'
    ];

    /**
     * The table buttons.
     *
     * @var array
     */
    protected $buttons = [
        'select' => [
            'data-file' => 'entry.id'
        ]
    ];

    /**
     * The table options.
     *
     * @var array
     */
    protected $options = [
        'title' => 'anomaly.field_type.file::message.choose_file'
    ];

    /**
     * Fired when query starts building.
     *
     * @param Builder $query
     */
    public function onQuerying(Builder $query)
    {
        if ($folders = $this->getFolders()) {
            $query->whereIn('folder_id', array_keys($folders));
        }
    }

    /**
     * Get the folders.
     *
     * @return array
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * Set the folders.
     *
     * @param array $folders
     * @return $this
     */
    public function setFolders(array $folders = [])
    {
        $this->folders = $folders;

        return $this;
    }
}
