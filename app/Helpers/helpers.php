<?php

use App\Models\Claim;
use App\Models\InsuranceProduct;
use App\Models\Quotation;
use App\Models\Role;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\RoleSlug;
use App\Shared\Domain\ValueObjects\GenericId;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

if (!function_exists('random_string')) {
    /**
     * Generate a random alphanumeric string of given length.
     *
     * @param mixed $modelClass Fully qualified model class name (e.g. App\Models\User::class)
     * @param string $column The code column name (default 'code')
     * @param int $length (default 20)
     * @return string
     */
    function random_string(mixed $modelClass, int $length = 20, string $column = 'code'): string
    {
        do {
            $uuid = Str::random($length);
            $exists = $modelClass::where($column, $uuid)->exists();
        } while ($exists);

        return $uuid;
    }
}

if (!function_exists('generate_unique_uuid')) {
    /**
     * Generate a unique UUID string for the given model and column.
     *
     * @param mixed $modelClass Fully qualified model class name (e.g. App\Models\User::class)
     * @param string $column The UUID column name (default 'uuid')
     * @return string
     */
    function generate_unique_uuid(mixed $modelClass, string $column = 'uuid'): string
    {
        do {
            $uuid = (string) Str::uuid();
            $exists = $modelClass::where($column, $uuid)->exists();
        } while ($exists);

        return $uuid;
    }
}

if (!function_exists('generate_quote_number')) {
    /**
     * Generate a quote number string for the model quotes.
     *
     * @param string $code
     * @return string
     */
    function generate_quote_number(string $code): string
    {
        $count = Quotation::where('insurance_product_code', $code)->count();

        // Start sequence from 1 when count = 0
        $sequence = $count + 1;

        // Pad to 10 digits (0000000001, 0000000002, ...)
        $formattedSequence = str_pad($sequence, 10, '0', STR_PAD_LEFT);

        $insuranceProduct = InsuranceProduct::where('code', $code)->value('name');

        return sprintf(
            'QN-%s-%s',
            strtoupper($insuranceProduct),
            $formattedSequence
        );
    }
}

if (!function_exists('generate_claim_number')) {
    /**
     * Generate a claim number string for the model claims.
     *
     * @param string $code
     * @return string
     */
    function generate_claim_number(string $code): string
    {
        $count = Claim::count();

        // Start sequence from 1 when count = 0
        $sequence = $count + 1;

        // Pad to 10 digits (0000000001, 0000000002, ...)
        $formattedSequence = str_pad($sequence, 10, '0', STR_PAD_LEFT);

        $insuranceProduct = InsuranceProduct::where('code', $code)->value('name');

        return sprintf(
            'CN-%s-%s',
            strtoupper($insuranceProduct),
            $formattedSequence
        );
    }
}

if (!function_exists('user_id')) {
    /**
     * Get the login user id else use 1 (System)
     *
     * @return int
     */
    function user_id(): int
    {
        return auth()->id() ?? 1;
    }
}

if (!function_exists('user_role_id')) {
    /**
     * Get the login user role_id
     *
     * @return int
     */
    function user_role_id(): int
    {
        return auth()->role_id();
    }
}


if (!function_exists('get_role_id_by_slug')) {
    /**
     * Get the role ID by slug
     *
     * @throws ModelNotFoundException
     */
    function get_role_id_by_slug(RoleSlug $slug): int
    {
        return Cache::rememberForever(
            "role_id_{$slug}",
            fn() => Role::where('slug', $slug->value)->firstOrFail()->id
        );
    }
}

if (!function_exists('get_product_by_code')) {
    /**
     * Get the role ID by slug
     *
     * @param string $code
     * @param string $column default value `id`
     * @return string
     */
    function get_product_by_code(string $code, string $column = 'id'): string
    {
        return Cache::rememberForever(
            "insurance_product_{$code}",
            fn() => InsuranceProduct::where('code', $code)->firstOrFail()->$column
        );
    }
}

if (!function_exists('array_non_null_values')) {
    /**
     * Extract only non-null values
     *
     * @param array $array
     * @return array
     */
    function array_non_null_values(
        array $array,
    ): array {
        return array_filter(
            $array,
            static fn($value) =>
            !is_null($value)
                && !(
                    is_string($value) && trim($value) === ''
                )
                && !(
                    is_array($value) && empty($value)
                )
        );
    }
}

if (!function_exists('array_old_values')) {
    /**
     * Extract old the keys and value getting changed
     *
     * @param mixed $model
     * @param array $array
     * @return array
     */
    function array_old_values(
        mixed $model,
        array $array,
    ): array {
        $oldValues = [];

        foreach ($array as $field => $newValue) {
            if (array_key_exists($field, $model->getAttributes())) {
                $oldValues[$field] = $model->getOriginal($field);
            }
        }

        return $oldValues;
    }
}


if (!function_exists('insurance_audit')) {
    /**
     * Persist an audit log entry for a model action.
     *
     * @param mixed       $model
     * @param AuditAction $action
     * @param array|null  $oldValues
     * @param array|null  $newValues
     */
    function insurance_audit(
        mixed $model,
        AuditAction $action,
        ?array $oldValues,
        ?array $newValues
    ): void {
        $model->audits()->create([
            'user_id'    => user_id(),
            'action'     => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'created_at' => Carbon::now(),
        ]);
    }
}

if (!function_exists('add_http_protocol')) {
    function add_http_protocol(string $urls, string $protocol = "http")
    {
        if (empty($urls) || $urls == '*') return ['*'];

        $parts = array_filter(
            array_map('trim', explode(',', $urls)),
            fn($url) => $url !== ''
        );

        return array_map(function ($url) use ($protocol) {
            $url = trim($url);

            if (!preg_match('#^https?://#', $url)) {
                $url = "$protocol://$url";
            }

            return $url;
        }, $parts);
    }
}

if (!function_exists('get_initials')) {
    function get_initials(string $name)
    {
        $words = array_values(array_filter(explode(" ", trim($name))));

        $initials = "";

        if (count($words) >= 1) {
            $initials .= strtoupper($words[0][0]); // first name
        }

        if (count($words) >= 2) {
            $initials .= strtoupper($words[count($words) - 1][0]); // last name
        }

        return $initials;
    }
}

if (!function_exists('encrypt')) {
    function encrypt(string | int $value)
    {
        return Hashids::encode($value);
    }
}

if (!function_exists('decrypt')) {
    function decrypt(string $value)
    {
        $decrypted = Hashids::decode($value);

        return $decrypted[0];
    }
}

if (!function_exists('getAuthenticatedUser')) {
    function getAuthenticatedUser()
    {
        return auth()->user();
    }
}


if (!function_exists('getAgentId')) {
    function getAgentId()
    {
        $user = auth()->user();
        if ($user->isAgent() || $user->isTeamLead()) {
            return GenericId::fromId($user->id);
        }

        return null;
    }
}

if (!function_exists('getId')) {
    function getId()
    {
        return auth()->id() ?? null;
    }
}

if (!function_exists('metaKeyValue')) {
    function metaKeyValue(string $column)
    {
        return DB::raw("MAX(CASE WHEN meta_key = '$column' THEN meta_value END) AS $column");
    }
}

if (!function_exists('keyValue')) {
    function keyValue(string $column)
    {
        return DB::raw("MAX(CASE WHEN key = '$column' THEN value END) AS $column");
    }
}
