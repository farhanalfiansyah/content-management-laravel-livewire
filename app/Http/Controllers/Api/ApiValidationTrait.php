<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ApiValidationTrait
{
    /**
     * Validate request data
     */
    protected function validateRequest(Request $request, array $rules, array $messages = [])
    {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        return $validator->validated();
    }

    /**
     * Common validation rules for posts
     */
    protected function getPostValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'title' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'content' => $isUpdate ? 'sometimes|required|string' : 'required|string',
            'status' => $isUpdate ? 'sometimes|required|in:draft,published' : 'required|in:draft,published',
            'short_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'published_at' => 'nullable|date',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ];

        if ($isUpdate) {
            $rules['slug'] = 'sometimes|nullable|string|max:255';
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:posts,slug';
        }

        return $rules;
    }

    /**
     * Common validation rules for pages
     */
    protected function getPageValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'title' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'body' => $isUpdate ? 'sometimes|required|string' : 'required|string',
            'status' => $isUpdate ? 'sometimes|required|in:draft,published' : 'required|in:draft,published',
        ];

        if ($isUpdate) {
            $rules['slug'] = 'sometimes|nullable|string|max:255';
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:pages,slug';
        }

        return $rules;
    }

    /**
     * Common validation rules for categories
     */
    protected function getCategoryValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'name' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
        ];

        if ($isUpdate) {
            $rules['slug'] = 'sometimes|nullable|string|max:255';
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:categories,slug';
        }

        return $rules;
    }

    /**
     * Validation messages
     */
    protected function getValidationMessages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'in' => 'The selected :attribute is invalid.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'unique' => 'The :attribute has already been taken.',
            'exists' => 'The selected :attribute is invalid.',
            'array' => 'The :attribute must be an array.',
            'date' => 'The :attribute is not a valid date.',
        ];
    }

    /**
     * Sanitize input data
     */
    protected function sanitizeInput(array $data): array
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return trim(strip_tags($value, '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6>'));
            }
            return $value;
        }, $data);
    }
} 