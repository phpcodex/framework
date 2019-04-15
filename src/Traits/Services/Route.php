<?php

namespace PHPCodex\Framework\Traits\Services;

use PHPCodex\Framework\Support\Filters\SlashSafe;

use \Closure;

trait Route
{

    private $matched = false;

    /**
     * @var array
     *
     * This should only hold data from routes which
     * match parameters that are not exact but
     * still required.
     */
    private $variables = [];

    public function matches($path, $against)
    {
        /**
         * Reset our variables becuase we've clearly not matched
         * a route that was successful.
         */
        $this->variables = [];

        /**
         * Count the amount of optional parameters we have as
         * we could have 2 optional parameters, which means
         * 1 MUST be forced and 1 should have a default
         * value assigned to it.
         */
        $options = 0;

        /**
         * Ensure we understand what both A and B are doing
         * while they are matched together.
         */
        $matchA = explode('/', $path);
        $matchB = explode('/', $against);

        /**
         * So long as the value of A is encompassed in the
         * { and } brackets, it's now a variable saved
         * and allowed to be used later.
         */
        foreach ($matchA as $key => $val) {
            if (
                (substr($val, 0, 1) == '{') &&
                substr($val, -1,1) == '}'
            ) {
                $options++;

                /**
                 * We can only assign the variable on this key if
                 * the corresponding pair matches.
                 */
                if (isset($matchB[$key])) {
                    $this->variables[substr($val, 1, -1)] = (new SlashSafe($matchB[$key]))->get();
                }
            } else {
                if ($matchA[$key] != $matchB[$key]) {
                    return false;
                }
            }
        }

        /**
         * Either we're an exact match on the route or simple
         * a parameter exists for us to count match with.
         */
        return $path == $against || count($matchA) == count($matchB) || count($matchA) - $options == count($matchB);
    }

    /**
     * @param $path
     * @param $action
     * @return bool|mixed
     *
     * A defined list of methods we actually want our route files
     * to have access to.
     */
    private function run($path, $action, $runType = 'get')
    {
        /**
         * Define what we are going to return to the calling
         * method/object.
         */
        $response = false;

        /**
         * Depending on what type of runnable action we are, we
         * may want to ensure we use specific logic here.
         */
        if (
            ($runType == 'get' && empty($this->request->post())) OR
            ($runType == 'post' && empty(!$this->request->post()))
        ) {

            /**
             * If we're not matching this path, then we have not hit
             * this route and should return back immediately to attempt
             * matching another.
             *
             * We should ensure that our routes match 1:1 and not n+1
             * otherwise it's hard to determine which route did the
             * calling action.
             */
            if (
                ($this->sapi == self::ACCESS_VIA_WEB && !$this->matched && $this->matches($path, $this->webPath)) OR
                ($this->sapi == self::ACCESS_VIA_CLI && !$this->matched && $this->matches($path,
                        implode(' ', $this->parameters)))
            ) {
                /**
                 * Set our matched state so we don't match another route.
                 * It's important we don't have a conflict. If we
                 * do... re-think your approach!
                 */
                $this->matched = true;

                /**
                 * If we're a closure, then we should ensure that we run
                 * the closure.
                 *
                 * If we're not a closure, then we need to break down
                 * what class we are going to load followed by the
                 * method we are going to run.
                 */
                if ($action instanceof closure) {

                    /**
                     * If we have no variables, let's run our closure and pass
                     * no data through, so the defaults apply.
                     */
                    if (empty($this->variables)) {
                        $response = $action();
                    } else {
                        /**
                         * We have data to pass through, so let's create some string value
                         * we can evaluate and push into the closure.
                         */
                        $data = implode("', '", $this->variables);
                        $response = eval($action($data));
                    }

                } else {
                    $action = explode('@', $action);

                    /**
                     * Establist the new class.
                     */
                    $call = new $action[0];

                    /**
                     * Set the variables in the new class to what
                     * we have discovered here.
                     */
                    $call->options = $this->variables;
                    $response = $call->{$action[1]}();
                }

            }

        }
        return $response;
    }

    public function get($path, $action)
    {
        /**
         * Very crude - I could use an implementation of a
         * Request class for this, but will take some time.
         */
        echo $this->run($path, $action, 'get');
    }

    public function post($path, $action)
    {
        /**
         * Very crude - I could use an implementation of a
         * Request class for this, but will take some time.
         */
        echo $this->run($path, $action, 'post');
    }
}