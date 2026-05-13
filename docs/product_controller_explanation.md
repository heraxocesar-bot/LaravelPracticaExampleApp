# Explicación Detallada del ProductController

Este documento explica paso a paso qué hace cada parte del archivo `app/Http/Controllers/ProductController.php`.

## 1. Importaciones y Namespace
```php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
```
*   **namespace**: Define dónde vive este archivo dentro de la estructura de carpetas.
*   **use App\Models\Product**: Importamos el modelo `Product` para poder interactuar con la tabla de productos en la base de datos.
*   **use Illuminate\Http\Request**: Importamos la clase `Request`, que contiene toda la información de la petición que hace el usuario (datos enviados, cabeceras, etc.).

---

## 2. Función `index()`
```php
public function index()
{
    return response()->json(Product::all());
}
```
*   **¿Qué hace?**: Obtiene una lista de **todos** los productos registrados.
*   **`Product::all()`**: Es un comando de Eloquent (el ORM de Laravel) que hace un `SELECT * FROM products` en la base de datos.
*   **`response()->json(...)`**: Convierte esa lista en un formato JSON para que sea fácil de leer por una aplicación frontend o móvil.
*   **Estado HTTP**: Por defecto devuelve un **200 OK**.

---

## 3. Función `store(Request $request)`
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
    ]);

    $product = Product::create($validated);

    return response()->json($product, 201);
}
```
*   **¿Qué hace?**: Crea un nuevo producto.
*   **`$request->validate([...])`**: Esta es la parte más importante de la lógica. Revisa que los datos enviados cumplan reglas:
    *   `required`: Es obligatorio.
    *   `numeric/integer`: Debe ser un número.
    *   `min:0`: No puede ser negativo.
    *   **Si falla**: Laravel detiene la ejecución y devuelve un error automático al usuario (422 Unprocessable Content).
*   **`Product::create($validated)`**: Si la validación pasa, guarda los datos en la base de datos.
*   **`201`**: Es el código de estado HTTP para **"Created"** (Creado).

---

## 4. Función `show(Product $product)`
```php
public function show(Product $product)
{
    return response()->json($product);
}
```
*   **¿Qué hace?**: Muestra los detalles de **un solo** producto.
*   **Route Model Binding**: Nota que recibimos `Product $product`. Laravel es inteligente: si el usuario pide `/api/products/5`, Laravel busca automáticamente el producto con ID 5 en la base de datos y nos lo entrega ya listo. Si no existe, devuelve un error 404 automáticamente.

---

## 5. Función `update(Request $request, Product $product)`
```php
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'sometimes|required|numeric|min:0',
        'stock' => 'sometimes|required|integer|min:0',
    ]);

    $product->update($validated);

    return response()->json($product);
}
```
*   **¿Qué hace?**: Modifica un producto que ya existe.
*   **`sometimes`**: Esta regla dice: "solo valida este campo si viene en la petición". Esto permite actualizar solo el nombre o solo el precio sin tener que enviar todo otra vez.
*   **`$product->update($validated)`**: Aplica los cambios en la base de datos.

---

## 6. Función `destroy(Product $product)`
```php
public function destroy(Product $product)
{
    $product->delete();

    return response()->json(null, 204);
}
```
*   **¿Qué hace?**: Borra un producto de la base de datos.
*   **`$product->delete()`**: Ejecuta el comando `DELETE` en SQL.
*   **`204`**: Es el código de estado para **"No Content"**. Significa: "Todo salió bien, pero no hay nada que mostrar porque el objeto ya no existe".
