<?php
/**
 * Контроллер AdminOrderController
 * Управление заказами в админпанели
 */
class AdminOrderController extends AdminBase
{
    /**
     * Action для страницы "Управление заказами"
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();
        // Получаем список заказов
        $ordersList = Order::getOrdersList();
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/index.php');
        return true;
    }
    /**
     * Action для страницы "Редактирование заказа"
     */
    public function actionUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();
        // Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена   
            // Получаем данные из формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date = $_POST['date'];
            $status = $_POST['status'];
            // Сохраняем изменения
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);
            // Перенаправляем пользователя на страницу управлениями заказами
            header("Location: /admin/order/view/$id");
        }
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }
    /**
     * Action для страницы "Просмотр заказа"
     */
    public function actionView($id)
    {
        // Проверка доступа
        self::checkAdmin();
        // Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);
        // Получаем массив с идентификаторами и количеством товаров
        $productsQuantity = json_decode($order['products'], true);
        // Получаем массив с индентификаторами товаров
        $productsIds = array_keys($productsQuantity);
        // Получаем список товаров в заказе
        $products = Product::getProductsByIds($productsIds);
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }
    /**
     * Action для страницы "Удалить заказ"
     */
    public function actionDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Удаляем заказ
            Order::deleteOrderById($id);
            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/order");
        }
        // Подключаем вид
        require_once(ROOT . '/views/admin_order/delete.php');
        return true;
    }
    
    public static function deleteOrderById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = 'DELETE FROM product_order WHERE id = :id';
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }
    /**
     * Редактирует заказ с заданным id
     * @param integer $id <p>id товара</p>
     * @param string $userName <p>Имя клиента</p>
     * @param string $userPhone <p>Телефон клиента</p>
     * @param string $userComment <p>Комментарий клиента</p>
     * @param string $date <p>Дата оформления</p>
     * @param integer $status <p>Статус <i>(включено "1", выключено "0")</i></p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function updateOrderById($id, $userName, $userPhone, $userComment, $date, $status)
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = "UPDATE product_order
            SET 
                user_name = :user_name, 
                user_phone = :user_phone, 
                user_comment = :user_comment, 
                date = :date, 
                status = :status 
            WHERE id = :id";
        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':date', $date, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }
}