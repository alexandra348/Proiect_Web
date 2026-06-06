<?php

require_once __DIR__ . '/../repositories/DrinkRepository.php';
require_once __DIR__ . '/../exceptions/DrinkException.php';

class DrinkService {

    private DrinkRepository $repository;

    public function __construct(DrinkRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getAll();
        } catch (PDOException $e) {
            throw new DrinkException("Failed to fetch drinks", 0, $e);
        }
    }

    public function findById($id): array
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new DrinkException("Invalid drink ID");
        }

        try {
            $drink = $this->repository->findById((int)$id);

            if (!$drink) {
                throw new DrinkException("Drink not found");
            }

            return $drink;

        } catch (PDOException $e) {
            throw new DrinkException("Failed to fetch drink", 0, $e);
        }
    }

    public function getByProvider($providerId): array
    {
        if (!is_numeric($providerId) || $providerId <= 0) {
            throw new DrinkException("Invalid provider ID");
        }

        try {
            return $this->repository->getByProvider((int)$providerId);
        } catch (PDOException $e) {
            throw new DrinkException("Failed to fetch provider drinks", 0, $e);
        }
    }

    public function create(array $data): bool
{
    $this->validateCreate($data);

    try {

        $imageUrl = null;

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $allowed = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file(
                $finfo,
                $_FILES['image']['tmp_name']
            );
            finfo_close($finfo);

            if (!in_array($mimeType, $allowed)) {
                throw new DrinkException(
                    "Invalid image format"
                );
            }

            $maxSize = 5 * 1024 * 1024;

            if ($_FILES['image']['size'] > $maxSize) {
                throw new DrinkException(
                    "Image exceeds 5MB"
                );
            }

            $uploadDir =
                __DIR__ .
                '/../../public/uploads/drinks/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $filename =
                uniqid('drink_', true) .
                '.' .
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                );

            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadDir . $filename
            )) {
                throw new DrinkException(
                    "Failed to save image"
                );
            }

            $imageUrl =
                '/uploads/drinks/' . $filename;
        }

        return $this->repository->create([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':provider_id' => $data['provider_id'],
            ':category_id' => $data['category_id'],
            ':image_url' => $imageUrl
        ]);

    } catch (PDOException $e) {
        throw new DrinkException("Failed to create drink",0,$e);
    }
}

    public function update($id, $data): bool
{
    if (!is_numeric($id) || $id <= 0) {
        throw new DrinkException("Invalid drink ID");
    }

    if (empty($data) && empty($_FILES['image'])) {
        throw new DrinkException("No fields provided for update");
    }


    try {

        $exists = $this->repository->findById((int)$id);

        if (!$exists) {
            throw new DrinkException("Drink not found");
        }

        if(isset($data['provider_id'])) {
            if ($exists[0]["provider_id"] != $data['provider_id']) {
                 throw new DrinkException("You cannot update this drink");
            }
        }
        

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === UPLOAD_ERR_OK
        ) {

            $allowed = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file(
                $finfo,
                $_FILES['image']['tmp_name']
            );
            finfo_close($finfo);

            if (!in_array($mimeType, $allowed)) {
                throw new DrinkException("Invalid image format");
            }

            $maxSize = 5 * 1024 * 1024;

            if ($_FILES['image']['size'] > $maxSize) {
                throw new DrinkException("Image exceeds 5MB");
            }

            $uploadDir =
                __DIR__ . '/../../public/uploads/drinks/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $filename =
                uniqid('drink_', true) .
                '.' .
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                );

            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadDir . $filename
            )) {
                throw new DrinkException("Failed to save image");
            }

            $data['image_url'] =
                '/uploads/drinks/' . $filename;
        }

        return $this->repository->update(
            (int)$id,
            $data
        );

    } catch (PDOException $e) {

        throw new DrinkException(
            "Failed to update drink",
            0,
            $e
        );
    }
}

    public function delete($id, $user): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new DrinkException("Invalid drink ID");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new DrinkException("Drink not found");
            }

            if($user->role === 'provider' && $exists['provider_id'] != $user->user_id){
               throw new DrinkException("You cannot delete this drink");
            }

            return $this->repository->delete((int)$id);

        } catch (PDOException $e) {
            throw new DrinkException("Failed to delete drink", 0, $e);
        }
    }

    // -------------------
    // VALIDATION
    // -------------------

    private function validateCreate(array $data): void
    {
        if (empty($data['name'])) {
            throw new DrinkException("Name is required");
        }

        if (!isset($data['price']) || $data['price'] < 0) {
            throw new DrinkException("Valid price is required");
        }

        if (empty($data['provider_id']) || !is_numeric($data['provider_id'])) {
            throw new DrinkException("Valid provider_id is required");
        }

        if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
            throw new DrinkException("Valid category_id is required");
        }
    }

}