<?php
/**
 * Functions used by the dokuwiki-template-vyfuk
 *
 * @author Miroslav Jarý <jason@vyfuk.mff.cuni.cz>
 * @copyright 2021, Výfuk
 * @license https://www.php.net/license/3_01.txt
 *
 */

namespace vyfukTemplate;

use Doku_Handler;
use Doku_Parser;
use dokuwiki\Menu\PageMenu;
use dokuwiki\Menu\UserMenu;
use dokuwiki\Parsing\Parser;

class tpl_functions {
    static function getPageTitle(string $ID) {
        if ($ID == "start")
            return "Tady je Výfučí!";
        elseif (!page_exists($ID))
            $prefix = "Stránka nenalezena";
        else
            $prefix = p_get_metadata($ID, 'title');

        return $prefix . ' • Výfuk';
    }

    static function draw_content(string $path) {
        if (page_exists($path)) {
            tpl_include_page($path);
        } else {
            echo "<p class='text-danger'>Chybějící stránka <b>{$path}</b>.</p>";
        }
    }

    static function drawNavItems(string $path, string $classes = '') {
        $path = wikiFN($path);
        if (file_exists($path)) {
            $items = self::parseMenuFile($path);
            $html = "<ul class='navbar-nav $classes'>";
            foreach ($items as $item) {
                if (count($item) == 1) { // Only render the link
                    $html .= "<li class='nav-item'>";
                    $html .= self::getNavLinkHTML($item[0]);
                    $html .= "</li>";
                } else { // Render dropdown
                    $html .= self::getNavDropdownHTML($item);
                }
            }
            $html .= "</ul>";
            echo $html;
        } else {
            echo "Soubor ${path} nenalezen.";
        }
    }

    static function drawNavUserItems() {
        $html = "<ul class='navbar-nav ms-auto'>";
        $html .= "<li class='nav-item dropdown'>";
        $html .= "<span class='nav-link dropdown-toggle' role='button' data-bs-toggle='dropdown'><i class='fa fa-cogs'></i>&nbsp;Nástroje</span>";
        $items = (new PageMenu())->getItems();
        $items = array_merge($items, (new UserMenu())->getItems());
        $html .= self::getNavAdminHTML($items);
        $html .= "</li>";
        $html .= "</ul>";
        echo $html;
    }

    private static function parseMenuFile(string $path) {
        $items = explode("\n", file_get_contents($path));
        $i = -1;
        $min_indent = -1; // This stores the first-level indent
        $data = [];
        foreach ($items as $item) {
            // Split the line to two parts - indentation and the link itself
            [$bullet, $link] = explode('*', $item, 2);
            $link = str_replace(['[', ']', '**', '__'], '', $link);
            $indent = substr_count($bullet, ' ');
            if ($min_indent < 0) {
                $min_indent = $indent;
            }

            $item_data = explode('|', $link);
            // If it's a 1st level link, store it in new array
            if ($indent <= $min_indent) {
                $i++;
                $data[] = [];
            }
            $data[$i][] = $item_data;
        }
        return $data;
    }

    private static function getNavLinkHTML(array $data, string $classes = 'nav-link', bool $dropdown = false) {
        $html = "";
        if ($dropdown) {
            $html .= "<a class='{$classes}' href='{$data[0]}' role='button' data-bs-toggle='dropdown'>";
        } else {
            $html .= "<a class='{$classes}' href='{$data[0]}'>";
        }
        if (count($data) > 2) { // There should be an icon
            $html .= "<i class='{$data[2]}'></i>&nbsp;";
        }
        $html .= "{$data[1]}</a>";
        return $html;
    }

    private static function getNavDropdownHTML(array $data) {
        $html = "<li class='nav-item dropdown'>";
        // Render the first item
        $html .= self::getNavLinkHTML($data[0], 'nav-link dropdown-toggle', true);
        $html .= "<ul class='dropdown-menu'>";
        // Render the rest in dropdown
        for ($i = 1; $i < count($data); $i++) {
            $html .= self::getNavLinkHTML($data[$i], 'dropdown-item');
        }
        $html .= "</ul>";
        $html .= "</li>";
        return $html;
    }

    private static function getNavAdminHTML(array $data) {
        global $INFO;
        $html = "<ul class='dropdown-menu'>";
        $html .= "<span class='dropdown-header text-center w-100'><i class='fa fa-user'></i>&nbsp;{$INFO['userinfo']['name']}</span>";
        foreach ($data as $item) {
            $item_data = [
                $item->getLink(),
                inlineSVG($item->getSvg()) . '&nbsp;' . $item->getLabel()
            ];
            $html .= self::getNavLinkHTML($item_data, 'dropdown-item');
        }
        $html .= "</ul>";
        return $html;
    }
}
