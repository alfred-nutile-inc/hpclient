<?php
namespace AlfredNutileInc\HPClient;

trait UserFromResource
{

    public function transformResouceToResourceName(array $results, $users)
    {
        return collect($results)->map(function ($result) use ($users) {
            $user = collect($users)->first(function ($user) use ($result) {
                return $user['_id'] == $result['resource'];
            });
            $result['user_name'] = sprintf("%s %s", array_get($user, 'firstName'), array_get($user, 'lastName'));
            return $result;
        })->toArray();
    }
}
