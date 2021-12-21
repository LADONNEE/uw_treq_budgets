#!/bin/bash

# This version of scheduled updates runs each command through the
# MCP utility so the runs and any output can be logged centrally

# Update budget data (EDW)
/www/mcp/bin/run --cd /www/budgets --art update:budgets
