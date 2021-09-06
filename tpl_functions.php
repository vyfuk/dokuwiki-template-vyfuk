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

use dokuwiki\Menu\PageMenu;
use dokuwiki\Menu\SiteMenu;
use dokuwiki\Menu\UserMenu;

class tpl_functions {
    static function getPageTitle(string $ID): string {
        if ($ID == "start"){
            return "Tady je Výfučí!";
        } elseif (!page_exists($ID)) {
            $prefix = "Stránka nenalezena";
        } else {
            $prefix = p_get_metadata($ID, 'title');
        }

        return $prefix . self::getPageTitleSuffix();
    }

    static function getPageTitleSuffix(): string {
        return " • Výfuk";
    }

    static function draw_content(string $path): void {
        if (page_exists($path)) {
            tpl_include_page($path);
        } else {
            echo "<p class='text-danger'>Chybějící stránka <b>{$path}</b>.</p>";
        }
    }

    static function draw_dev_warning(): void {
        if (tpl_getConf('display_dev_warning')) {
            echo "<div class='alert alert-warning position-fixed bottom-0 end-0 m-3'>Upozornění: Toto je testovací verze webu!</div>";
        }
    }

    static function drawNavItems(string $path, string $classes = ''): void {
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

    static function drawNavUserItems(): void {
        // Define the menu items
        // Null symbolizes the dropdown divider
        $items = (new SiteMenu())->getItems();
        $items[] = null;
        $items = array_merge($items, (new PageMenu())->getItems());
        $items[] = null;
        $items = array_merge($items, (new UserMenu())->getItems());

        // Render the html
        $html = "<ul class='navbar-nav ms-auto'>";
        $html .= "<li class='nav-item dropdown'>";
        $html .= "<span class='nav-link py-1 dropdown-toggle' role='button' data-bs-toggle='dropdown'><i class='fa fa-cogs'></i>&nbsp;Nástroje</span>";
        $html .= self::getNavAdminHTML($items);
        $html .= "</li>";
        $html .= "</ul>";
        echo $html;
    }

    private static function parseMenuFile(string $path): array {
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
            // Convert dw syntax to html link
            if (page_exists($item_data[0])) {
                $item_data[0] = wl(ltrim($item_data[0]));
            }
            // If it's a 1st level link, store it in new array
            if ($indent <= $min_indent) {
                $i++;
                $data[] = [];
            }
            $data[$i][] = $item_data;
        }
        return $data;
    }

    private static function getNavLinkHTML(array $data, string $classes = 'nav-link py-2', bool $dropdown = false): string {
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

    private static function getNavDropdownHTML(array $data): string {
        $html = "<li class='nav-item dropdown'>";
        // Render the first item
        $html .= self::getNavLinkHTML($data[0], 'nav-link dropdown-toggle py-2', true);
        $html .= "<ul class='dropdown-menu'>";
        // Render the rest in dropdown
        for ($i = 1; $i < count($data); $i++) {
            $html .= self::getNavLinkHTML($data[$i], 'dropdown-item d-flex align-items-center');
        }
        $html .= "</ul>";
        $html .= "</li>";
        return $html;
    }

    private static function getNavAdminHTML(array $data): string {
        global $INFO;

        // Blacklist of item types we don't want in the menu (cuz nobody uses them)
        $blacklist = ["recent", "menubutton", "backlink", "top"];

        //HTML definition
        $html = "<ul class='dropdown-menu dropdown-menu-end end-0'>";
        $html .= "<span class='dropdown-header text-center w-100'><i class='fa fa-user'></i>&nbsp;{$INFO['userinfo']['name']}</span>";
        foreach ($data as $item) {
            if (is_null($item)) {// Handle divider rendering
                $html .= "<div class='dropdown-divider'></div>";
            } else if (in_array($item->getType(), $blacklist)) { // Handle blacklisted items
                continue;
            } else {
                $item_data = [
                    $item->getLink(),
                    inlineSVG($item->getSvg()) . '&nbsp;' . $item->getLabel()
                ];
                $html .= self::getNavLinkHTML($item_data, 'dropdown-item d-flex align-items-center');
            }
        }
        $html .= "</ul>";
        return $html;
    }
}