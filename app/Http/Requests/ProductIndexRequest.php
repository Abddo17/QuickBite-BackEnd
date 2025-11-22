<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      // Pagination
      'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],

      // Filtering
      'category_id' => ['sometimes', 'integer', 'exists:categories,categoryId'],
      'search'      => ['sometimes', 'string', 'max:255'],
      'min_price'   => ['sometimes', 'numeric', 'min:0'],
      'max_price'   => ['sometimes', 'numeric', 'gte:min_price'],
      'type'        => ['sometimes', 'string', 'max:100'],

      // Sorting
      'sort_by'  => ['sometimes', 'in:id,nom,prix,type,created_at,updated_at'],
      'sort_dir' => ['sometimes', 'in:asc,desc'],
    ];
  }

  /**
   * Sanitize and prepare validated input data before controller usage.
   * This ensures safe, predictable query parameters.
   */
  protected function prepareForValidation(): void
  {
    $this->merge([
      'per_page' => $this->input('per_page', 50),
      'sort_by'  => $this->input('sort_by', 'created_at'),
      'sort_dir' => strtolower($this->input('sort_dir', 'desc')),
    ]);
  }

  /**
   * Custom messages for validation errors.
   */
  public function messages(): array
  {
    return [
      'category_id.exists' => 'The selected category does not exist.',
      'sort_by.in'         => 'Invalid sort field. Allowed: id, nom, prix, type, created_at, updated_at.',
      'sort_dir.in'        => 'Sort direction must be either asc or desc.',
      'max_price.gte'      => 'The maximum price must be greater than or equal to the minimum price.',
    ];
  }
}
