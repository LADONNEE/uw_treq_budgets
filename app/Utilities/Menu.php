<?php
namespace App\Utilities;

use App\Auth\User;

class Menu
{
    /**
     * @var array
     */
    private $menu;
    /**
     * @var User
     */
    private $user;
    private $id;

    public function __construct(array $menu, User $user = null)
    {
        $this->menu = $menu;
        $this->user = ($user instanceof User) ? $user : user();
    }

    public static function make($menu_file)
    {
        $menu = include resource_path() . '/views/' . $menu_file;
        $m = new self($menu);
        return $m->generate();
    }

    public function generate()
    {
        $this->id = 0;
        $out = [];
        foreach ($this->menu as $title => $items) {
            $sectionItems = $this->generateItems($items);
            if (count($sectionItems)) {
                $out[$title] = $sectionItems;
            }
        }
        return $out;
    }

    public function generateItems(array $items)
    {
        $out = [];
        foreach ($items as $item) {
            $name = $item[0];
            $href = $item[1];
            if (!$this->allowed($item)) {
                continue;
            }
            $out[$name] = $href;
        }
        return $out;
    }

    public function allowed($data)
    {
        if (!isset($data[2])) {
            return true;
        }
        return hasRole($data[2], $this->user);
    }
}
