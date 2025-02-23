<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\Interfaces\FromClauseInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\LimitClauseInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\OrderByInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\WhereClauseInterface;

interface PurgeInterface extends FromClauseInterface, LimitClauseInterface, OrderByInterface, StatementInterface, WhereClauseInterface {
}
