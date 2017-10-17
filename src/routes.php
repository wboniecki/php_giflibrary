<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Service\CategoryService;
use Service\GifService;
use Service\UsersService;
use Service\UsersGifFavoriteService;
use Web\Color;
use Entities\CategoryEntity;
use Entities\GifEntity;
use Entities\UsersEntity;
use Entities\UsersGifFavoriteEntity;

$app->get('/category/test/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $service = new CategoryService($this->db);
    $category = $service->findById($id);

    $response = $this->renderer->render($response, "category_info.phtml", ["category" => $category]);
    return $response;
});

$app->get('/gif/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $gifService = new GifService($this->db);
    $gif = $gifService->findById($id);
    if ($gif != null) {
        $gifs = array();
        $gifs[] = $gif;
        $categoryId = $gif->getCategoryId();
        $categoryService = new CategoryService($this->db);
        $categories = $categoryService->findAll();
        $category = $categoryService->findById($categoryId);

        if (isset($_SESSION['user_session'])) {
            $usersService = new UsersService($this->db);
            $currentUser = $usersService->findByName($_SESSION['user_name']);
            $gifMenu = false;
            if ($currentUser->getPrivilege() > 0 || ($gif->getUsername() == $currentUser->getName())) {
                $gifMenu = true;
            }
            $usersGifService = new UsersGifFavoriteService($this->db);
            $userFavorites = $usersGifService->getUserFavoriteGifsId($_SESSION['user_id']);
            $response = $this->renderer->render($response, "header.phtml", [
                "usersGifService" => $usersGifService,
                "userFavorites" => $userFavorites,
                "gifs" => $gifs,
                "categoryName" => $category->getName(),
                "categoryColor" => $category->getColorCode(),
                "categories" => $categories,
                "gifMenu" => $gifMenu,
                "content" => "main.phtml"
            ]);
        } else {
            $response = $this->renderer->render($response, "header.phtml", [
                "gifs" => $gifs,
                "categoryName" => $category->getName(),
                "categoryColor" => $category->getColorCode(),
                "categories" => $categories,
                "content" => "main.phtml"
            ]);
        }
        return $response;
    } else {
        echo "Gif do not exist!";
        //return $response->withRedirect('/');
    }
});

$app->get('/category/add', function (Request $request, Response $response) {
    $usersService = new UsersService($this->db);
    if (isset($_SESSION['user_session']) && $usersService->findByName($_SESSION['user_name'])->getPrivilege() > 0) {
        $colors = Color::values();
        $response = $this->renderer->render($response, "header.phtml", [
            "colors" => $colors,
            "content" => "category_add.phtml"
            ]);
        return $response;
    } else {
        return $response->withRedirect('/');
    }

});

$app->post('/category/add', function (Request $request, Response $response) {
    $service = new CategoryService($this->db);
    $service->save(new CategoryEntity($_POST));
    return $response->withRedirect('/categories');
});

$app->get('/add', function (Request $request, Response $response) {
    if (isset($_SESSION['user_session'])) {
        $categoryService = new CategoryService($this->db);
        $categories = $categoryService->findAll();
        echo "Dodawanie gifa - form";
        $response = $this->renderer->render($response, "header.phtml", [
            "categories" => $categories,
            "content" => "gif_add.phtml"
        ]);
        return $response;
    }

    return $response->withRedirect('/');

});

$app->post('/add', function (Request $request, Response $response) {
    print_r($_POST);
    $gif = new GifEntity($_POST);
    $date = new DateTime('now');
    $gif->setDateUpload($date->format('Y-m-d H:i:s'));
    //$gif->setBytes(file_get_contents($_FILES['bytes']['tmp_name']));
    $gif->setBytes(fread(fopen($_FILES['bytes']['tmp_name'], "rb"), filesize($_FILES['bytes']['tmp_name'])));
    $gif->setUsername($_SESSION['user_name']);
    $service = new GifService($this->db);
    $newGif = $service->save($gif);
    return $response->withRedirect('/gif/' . $newGif->getId());
    //print_r(fread(fopen($_FILES['bytes']['tmp_name'], "rb"), filesize($_FILES['bytes']['tmp_name'])));
    //print_r($_FILES['bytes']['tmp_name']);
    //print_r(file_get_contents($_FILES['bytes']['tmp_name']));

});

$app->get('/favorites', function (Request $request, Response $response) {
    $gifs = array();
    $categoryService = new CategoryService($this->db);
    $categories = $categoryService->findAll();
    if (isset($_SESSION['user_session'])) {
        $gifService = new GifService($this->db);
        $usersGifService = new UsersGifFavoriteService($this->db);
        $userFavorites = $usersGifService->getUserFavoriteGifsId($_SESSION['user_id']);
        foreach ($userFavorites as $favoriteId) {
            $gifs[] = $gifService->findById($favoriteId);
        }
        $response = $this->renderer->render($response, "header.phtml", [
            "usersGifService" => $usersGifService,
            "userFavorites" => $userFavorites,
            "gifs" => $gifs,
            "categories" => $categories,
            "content" => "main.phtml"
        ]);
    } else {
        // redirect to main page
        return $response->withRedirect('/');
    }
    return $response;
});

$app->get('/{username}/gifs', function (Request $request, Response $response, $args) {
    $username = $args['username'];
    $categoryService = new CategoryService($this->db);
    $categories = $categoryService->findAll();
    $gifService = new GifService($this->db);
    $gifs = $gifService->findByUserName($username);
    if (isset($_SESSION['user_session'])) {
        $usersGifService = new UsersGifFavoriteService($this->db);
        $userFavorites = $usersGifService->getUserFavoriteGifsId($_SESSION['user_id']);
        $response = $this->renderer->render($response, "header.phtml", [
            "usersGifService" => $usersGifService,
            "userFavorites" => $userFavorites,
            "gifs" => $gifs,
            "categories" => $categories,
            "content" => "main.phtml"
        ]);
    } else {
        $response = $this->renderer->render($response, "header.phtml", [
            "gifs" => $gifs,
            "categories" => $categories,
            "content" => "main.phtml"
        ]);
    }
    return $response;
});


$app->get('/mygifs', function (Request $request, Response $response) {
    $gifs = array();
    $categoryService = new CategoryService($this->db);
    $categories = $categoryService->findAll();
    if (isset($_SESSION['user_session'])) {
        $gifService = new GifService($this->db);
        $gifs = $gifService->findByUserName($_SESSION['user_name']);
        $usersGifService = new UsersGifFavoriteService($this->db);
        $userFavorites = $usersGifService->getUserFavoriteGifsId($_SESSION['user_id']);
        $response = $this->renderer->render($response, "header.phtml", [
            "usersGifService" => $usersGifService,
            "userFavorites" => $userFavorites,
            "gifs" => $gifs,
            "categories" => $categories,
            "content" => "main.phtml"
        ]);
    } else {
        // redirect to main page
        return $response->withRedirect('/');
    }
    return $response;
});

$app->get('/', function (Request $request, Response $response) {
    $gifService = new GifService($this->db);
    $gifs = $gifService->findAll();
    $categoryService = new CategoryService($this->db);
    $categories = $categoryService->findAll();
    if (isset($_SESSION['user_session'])) {
        $usersGifService = new UsersGifFavoriteService($this->db);
        $userFavorites = $usersGifService->getUserFavoriteGifsId($_SESSION['user_id']);
        $response = $this->renderer->render($response, "header.phtml", [
            "usersGifService" => $usersGifService,
            "userFavorites" => $userFavorites,
            "gifs" => $gifs,
            "categories" => $categories,
            "content" => "main.phtml"
        ]);
    } else {
        $response = $this->renderer->render($response, "header.phtml", [
            "gifs" => $gifs,
            "categories" => $categories,
            "content" => "main.phtml"
        ]);
    }
    return $response;
});

$app->get('/categories', function (Request $request, Response $response) {
    $service = new CategoryService($this->db);
    $categories = $service->findAll();

    $usersService = new UsersService($this->db);
    if (isset($_SESSION['user_session'])) {
        $currentUser = $usersService->findByName($_SESSION['user_name']);
        $categoryMenu = false;
        if ($currentUser->getPrivilege() > 0) {
            $categoryMenu = true;
        }
        return $this->renderer->render($response, "header.phtml", [
            "categories" => $categories,
            "categoryMenu" => $categoryMenu,
            "content" => "categories.phtml"
        ]);
    } else {
        return $this->renderer->render($response, "header.phtml", [
            "categories" => $categories,
            "content" => "categories.phtml"
        ]);
    }
});

$app->get('/category/{id}/gifs', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $gifService = new GifService($this->db);
    $gifs = $gifService->findByCategoryId($id);
    $categoryService = new CategoryService($this->db);
    $category = $categoryService->findById($id);
    if ($category != null) {

        $categories = $categoryService->findAll();
        if (isset($_SESSION['user_session'])) {
            $usersGifService = new UsersGifFavoriteService($this->db);
            $userFavorites = $usersGifService->getUserFavoriteGifsId($_SESSION['user_id']);
            $response = $this->renderer->render($response, "header.phtml", [
                "usersGifService" => $usersGifService,
                "userFavorites" => $userFavorites,
                "gifs" => $gifs,
                "categories" => $categories,
                "categoryName" => $category->getName(),
                "categoryColor" => $category->getColorCode(),
                "content" => "main.phtml"
            ]);
        } else {
            $response = $this->renderer->render($response, "header.phtml", [
                "gifs" => $gifs,
                "categories" => $categories,
                "categoryName" => $category->getName(),
                "categoryColor" => $category->getColorCode(),
                "content" => "main.phtml"
            ]);
        }
        return $response;
    } else {
        // category do not exist
        //return $response->withRedirect('/');
        echo "Category not exist";
    }
});

$app->get('/register', function (Request $request, Response $response) {
    if (!isset($_SESSION['user_session'])) {
        return $this->renderer->render($response, "header.phtml", [
            "content" => "register.phtml"
        ]);
    } else {
        echo "Wyloguj sie aby zobaczyc ta strone";
    }
});

$app->post('/register', function (Request $request, Response $response) {
    $user = new UsersEntity($_POST);
    $usersService = new UsersService($this->db);
    $usersService->save($user);
    return $response->withRedirect('/');
});

$app->get('/login', function (Request $request, Response $response) {
    if (!isset($_SESSION['user_session'])) {
        return $this->renderer->render($response, "header.phtml", [
            "content" => "login.phtml"
        ]);
    } else {
        echo "Jesteś juz zalogowany!";
    }

});

$app->post('/login', function (Request $request, Response $response) {
    print_r($_POST);
    $usersService = new UsersService($this->db);
    if (!isset($_SESSION['user_session'])) {
        if ($usersService->login($_POST['name'], $_POST['pass'])) {
            return $response->withRedirect('/');
        } else {
            echo "błedny login lub hasło";
        }
    } else {
        echo "nie zalogowano";
        return $request;
    }
});

$app->get('/logout', function (Request $request, Response $response) {
    if (isset($_SESSION['user_session'])) {
        $usersService = new UsersService($this->db);
        if ($usersService->logout()) {
            // ok
            return $response->withRedirect('/');
        }
    } else {
        echo "Nie jestes zalogowany";
    }
});

$app->get('/markfavorite/{gifId}', function (Request $request, Response $response, $args) {
    if (!isset($_SESSION['user_session'])) {
        return $this->renderer->render($response, "login.phtml");
    } else {
        $gifId = (int)$args['gifId'];
        $usersGifFavoriteService = new UsersGifFavoriteService($this->db);
        $record = $usersGifFavoriteService->findFavorite($_SESSION['user_id'], $gifId);
        if ($record->getId() != null) {
            $usersGifFavoriteService->updateFavorite($record);
        } else {
            echo "Dodajemy do bazy";
            $newFavorite = new UsersGifFavoriteEntity([
                "user_id" => $_SESSION['user_id'],
                "gif_id" => $gifId,
                "favorite" => true
            ]);
            print_r($newFavorite);
            $usersGifFavoriteService->save($newFavorite);
            $response = $response->withRedirect('/');
            return $response;
        }
    }
});

$app->get('/delete/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    if (!isset($_SESSION['user_session'])) {
        //error handling
        return $response->withRedirect('/');
    } else {
        $gifService = new GifService($this->db);
        $gif = $gifService->findById($id);
        if ($gif != null) {
            $usersService = new UsersService($this->db);
            $user = $usersService->findByName($_SESSION['user_name']);
            if (($user->getName() == $gif->getUsername() || $user->getPrivilege() > 0) && $gif) {
                $gifService->delete($gif);
                $usersGifFavoritesService = new UsersGifFavoriteService($this->db);
                $usersGifFavoritesService->deleteWithGif($gif->getId());
                echo "Usuwamy";
                return $response->withRedirect('/');
            } else {
                // error handling
                echo "cos nie tak";
                //return $response->withRedirect('/');
            }
        } else {
            //gif do not exist
            echo "Gif do not exist!";
        }
    }
});
